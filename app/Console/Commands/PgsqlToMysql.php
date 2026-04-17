<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Migrate all data from Replit's PostgreSQL database to cPanel MySQL.
 *
 * Prerequisites:
 *   - MYSQL_DATABASE_URL (or DB_HOST + DB_DATABASE + DB_USERNAME + DB_MYSQL_PASSWORD)
 *     must be set so the 'mysql' connection can reach the cPanel server.
 *   - DATABASE_URL must point to Replit's PostgreSQL so the 'pgsql' connection works.
 *   - Migrations must have already been run against MySQL:
 *       DB_CONNECTION=mysql php artisan migrate
 *
 * Usage:
 *   php artisan db:pgsql-to-mysql                        # migrate all tables
 *   php artisan db:pgsql-to-mysql --tables=users,admins  # specific tables only
 *   php artisan db:pgsql-to-mysql --clear                # truncate before inserting
 *   php artisan db:pgsql-to-mysql --dry-run              # preview without writing
 *   php artisan db:pgsql-to-mysql --chunk=500            # rows per batch (default 200)
 */
class PgsqlToMysql extends Command
{
    protected $signature = 'db:pgsql-to-mysql
                            {--tables=    : Comma-separated list of tables to migrate (default: all)}
                            {--clear      : Truncate MySQL tables before inserting}
                            {--dry-run    : Show what would be migrated without writing}
                            {--chunk=200  : Number of rows per INSERT batch}';

    protected $description = 'Migrate all data from Replit PostgreSQL to cPanel MySQL (schema must already exist on MySQL)';

    /** Tables that are never copied (Laravel internals / sensitive tokens) */
    private const SKIP_TABLES = [
        'migrations',
        'personal_access_tokens',
        'password_reset_tokens',
        'password_resets',
    ];

    private int $totalRows   = 0;
    private array $errors    = [];
    private array $tableStats = [];

    public function handle(): int
    {
        $dryRun    = (bool) $this->option('dry-run');
        $clear     = (bool) $this->option('clear');
        $chunkSize = max(1, (int) $this->option('chunk'));
        $only      = $this->option('tables')
            ? array_map('trim', explode(',', $this->option('tables')))
            : [];

        if ($dryRun) {
            $this->warn('DRY RUN — no data will be written to MySQL.');
        }

        // Verify connections
        $this->info('Verifying PostgreSQL connection (source)…');
        try {
            DB::connection('pgsql')->getPdo();
            $this->info('  PostgreSQL: OK');
        } catch (\Exception $e) {
            $this->error('  PostgreSQL connection failed: ' . $e->getMessage());
            return 1;
        }

        $this->info('Verifying MySQL connection (target)…');
        try {
            DB::connection('mysql')->getPdo();
            $this->info('  MySQL: OK');
        } catch (\Exception $e) {
            $this->error('  MySQL connection failed: ' . $e->getMessage());
            $this->line('  Make sure MYSQL_DATABASE_URL or DB_HOST/DB_DATABASE/DB_USERNAME/DB_MYSQL_PASSWORD are set.');
            return 1;
        }

        // Discover tables from PostgreSQL public schema
        $tables = $this->discoverPgsqlTables();
        $skipped = array_intersect($tables, self::SKIP_TABLES);
        $tables  = array_diff($tables, self::SKIP_TABLES);

        if (!empty($only)) {
            $tables = array_intersect($tables, $only);
        }

        if (empty($tables)) {
            $this->warn('No tables found to migrate.');
            return 0;
        }

        if (!empty($skipped)) {
            $this->warn(
                'Skipping system/security tables (not migrated): ' . implode(', ', $skipped) . "\n" .
                "  To include any of these, pass them explicitly via --tables."
            );
            $this->newLine();
        }

        $this->info('Tables to migrate: ' . implode(', ', $tables));
        $this->newLine();

        if ($clear && !$dryRun) {
            $this->clearMysqlTables($tables);
        }

        $bar = $this->output->createProgressBar(count($tables));
        $bar->start();

        foreach ($tables as $table) {
            $this->migrateTable($table, $dryRun, $chunkSize);
            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        // Summary
        $this->info('Migration complete.');
        $this->table(
            ['Metric', 'Value'],
            [
                ['Tables processed', count($tables)],
                ['Total rows migrated', number_format($this->totalRows)],
                ['Errors', count($this->errors)],
            ]
        );

        if (!empty($this->tableStats)) {
            $this->newLine();
            $this->info('Per-table row counts (MySQL after import):');
            $this->table(['Table', 'Source (pgsql)', 'Destination (mysql)', 'Status'], $this->tableStats);
        }

        if (!empty($this->errors)) {
            $this->newLine();
            $this->warn('Errors (first 30):');
            foreach (array_slice($this->errors, 0, 30) as $err) {
                $this->line("  - {$err}");
            }
            if (count($this->errors) > 30) {
                $this->line('  … and ' . (count($this->errors) - 30) . ' more.');
            }
        }

        return count($this->errors) > 0 ? 1 : 0;
    }

    // ─────────────────────────────────────────────────────────────────
    // Discovery
    // ─────────────────────────────────────────────────────────────────

    private function discoverPgsqlTables(): array
    {
        $rows = DB::connection('pgsql')->select(
            "SELECT tablename FROM pg_tables WHERE schemaname = 'public' ORDER BY tablename"
        );
        return array_column($rows, 'tablename');
    }

    // ─────────────────────────────────────────────────────────────────
    // Clear
    // ─────────────────────────────────────────────────────────────────

    private function clearMysqlTables(array $tables): void
    {
        $this->info('Clearing MySQL tables…');
        DB::connection('mysql')->statement('SET FOREIGN_KEY_CHECKS=0');
        try {
            foreach ($tables as $table) {
                try {
                    DB::connection('mysql')->table($table)->truncate();
                } catch (\Exception $e) {
                    // table may not exist yet on MySQL — skip silently
                }
            }
        } finally {
            DB::connection('mysql')->statement('SET FOREIGN_KEY_CHECKS=1');
        }
        $this->info('Tables cleared.');
        $this->newLine();
    }

    // ─────────────────────────────────────────────────────────────────
    // Migration
    // ─────────────────────────────────────────────────────────────────

    private function migrateTable(string $table, bool $dryRun, int $chunkSize): void
    {
        // Check table exists on MySQL target
        if (!$dryRun && !$this->mysqlTableExists($table)) {
            $this->errors[] = "[{$table}] Table does not exist on MySQL — run migrations first.";
            $this->tableStats[] = [$table, '?', '— missing on MySQL', 'SKIP'];
            return;
        }

        $sourceCount = 0;
        $inserted    = 0;

        try {
            $sourceCount = DB::connection('pgsql')->table($table)->count();
        } catch (\Exception $e) {
            $this->errors[] = "[{$table}] Cannot count source rows: " . $e->getMessage();
            return;
        }

        if ($sourceCount === 0) {
            $this->tableStats[] = [$table, '0', '0', 'OK (empty)'];
            return;
        }

        if ($dryRun) {
            $this->totalRows += $sourceCount;
            $this->tableStats[] = [$table, number_format($sourceCount), '— dry run', 'DRY RUN'];
            return;
        }

        // Get MySQL column info to handle type coercions
        $mysqlColumns = $this->getMysqlColumnTypes($table);

        // Determine if the table has an 'id' column for ordered chunking
        $hasId = $this->pgsqlColumnExists($table, 'id');

        DB::connection('mysql')->statement('SET FOREIGN_KEY_CHECKS=0');
        try {
            $query = DB::connection('pgsql')->table($table);
            if ($hasId) {
                $query->orderBy('id');
            }

            $query->chunk($chunkSize, function ($rows) use ($table, $mysqlColumns, &$inserted) {
                $batch = [];
                foreach ($rows as $row) {
                    $batch[] = $this->coerceRow((array) $row, $mysqlColumns);
                }
                try {
                    DB::connection('mysql')->table($table)->insert($batch);
                    $inserted += count($batch);
                    $this->totalRows += count($batch);
                } catch (\Exception $e) {
                    // Try row-by-row on batch failure
                    foreach ($batch as $record) {
                        try {
                            DB::connection('mysql')->table($table)->insert($record);
                            $inserted++;
                            $this->totalRows++;
                        } catch (\Exception $re) {
                            $this->errors[] = "[{$table}] " . substr($re->getMessage(), 0, 200);
                        }
                    }
                }
            });
        } catch (\Exception $e) {
            $this->errors[] = "[{$table}] " . substr($e->getMessage(), 0, 200);
        } finally {
            DB::connection('mysql')->statement('SET FOREIGN_KEY_CHECKS=1');
        }

        $destCount = 0;
        try {
            $destCount = DB::connection('mysql')->table($table)->count();
        } catch (\Exception $e) {
            // ignore
        }

        $status = ($inserted === $sourceCount) ? 'OK' : 'PARTIAL';
        $this->tableStats[] = [
            $table,
            number_format($sourceCount),
            number_format($destCount),
            $status,
        ];
    }

    // ─────────────────────────────────────────────────────────────────
    // Type coercion: PostgreSQL → MySQL
    // ─────────────────────────────────────────────────────────────────

    /**
     * Returns [column => data_type] from MySQL information_schema.
     * data_type values: tinyint(1) = boolean proxy, etc.
     */
    private function getMysqlColumnTypes(string $table): array
    {
        try {
            $dbName = DB::connection('mysql')->getDatabaseName();
            $rows   = DB::connection('mysql')->select(
                "SELECT COLUMN_NAME, DATA_TYPE, COLUMN_TYPE
                 FROM information_schema.COLUMNS
                 WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ?",
                [$dbName, $table]
            );
            $map = [];
            foreach ($rows as $row) {
                $map[$row->COLUMN_NAME] = [
                    'data_type'   => $row->DATA_TYPE,
                    'column_type' => $row->COLUMN_TYPE,
                ];
            }
            return $map;
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Coerce a PostgreSQL row (assoc array) so values are safe for MySQL:
     *  - boolean true/false  → 1/0
     *  - PHP null stays null
     *  - arrays/objects      → JSON string
     *  - large integers that exceed MySQL BIGINT signed range → string cast
     */
    private function coerceRow(array $row, array $mysqlColumns): array
    {
        $out = [];
        foreach ($row as $col => $value) {
            $mysqlType = $mysqlColumns[$col]['data_type']   ?? null;
            $colType   = $mysqlColumns[$col]['column_type'] ?? null;

            if ($value === null) {
                $out[$col] = null;
                continue;
            }

            // PHP bool (PostgreSQL returns actual booleans)
            if (is_bool($value)) {
                $out[$col] = $value ? 1 : 0;
                continue;
            }

            // Arrays or objects from JSON/JSONB columns → JSON string
            if (is_array($value) || is_object($value)) {
                $out[$col] = json_encode($value, JSON_UNESCAPED_UNICODE);
                continue;
            }

            // MySQL tinyint(1) is typically a boolean proxy
            if ($colType === 'tinyint(1)' && ($value === 't' || $value === 'f')) {
                $out[$col] = $value === 't' ? 1 : 0;
                continue;
            }

            // PostgreSQL boolean literals 't'/'f' in string columns → 1/0
            if (in_array($mysqlType, ['tinyint', 'bit']) && ($value === 't' || $value === 'f')) {
                $out[$col] = $value === 't' ? 1 : 0;
                continue;
            }

            // PostgreSQL zero-date strings → NULL
            if (in_array($mysqlType, ['datetime', 'timestamp', 'date'])) {
                if ($value === '0000-00-00 00:00:00' || $value === '0000-00-00') {
                    $out[$col] = null;
                    continue;
                }
            }

            $out[$col] = $value;
        }
        return $out;
    }

    // ─────────────────────────────────────────────────────────────────
    // Helpers
    // ─────────────────────────────────────────────────────────────────

    private function pgsqlColumnExists(string $table, string $column): bool
    {
        try {
            $row = DB::connection('pgsql')->selectOne(
                "SELECT 1 FROM information_schema.columns
                 WHERE table_schema = 'public' AND table_name = ? AND column_name = ?",
                [$table, $column]
            );
            return $row !== null;
        } catch (\Exception $e) {
            return false;
        }
    }

    private function mysqlTableExists(string $table): bool
    {
        try {
            $dbName = DB::connection('mysql')->getDatabaseName();
            $count  = DB::connection('mysql')->selectOne(
                "SELECT COUNT(*) as cnt FROM information_schema.TABLES
                 WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ?",
                [$dbName, $table]
            );
            return ($count->cnt ?? 0) > 0;
        } catch (\Exception $e) {
            return false;
        }
    }
}
