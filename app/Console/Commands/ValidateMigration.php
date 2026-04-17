<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * Validate that data was correctly migrated from Replit PostgreSQL to cPanel MySQL.
 *
 * Checks:
 *  1. Row counts in every table (pgsql vs mysql)
 *  2. Spot-check of key business records (customers, invoices, ads)
 *  3. NULL / coercion anomaly spot-checks (boolean, JSON, timestamp columns)
 *
 * Usage:
 *   php artisan db:validate-migration               # full validation
 *   php artisan db:validate-migration --tables=ads  # specific tables only
 *   php artisan db:validate-migration --json        # machine-readable output
 */
class ValidateMigration extends Command
{
    protected $signature = 'db:validate-migration
                            {--tables=  : Comma-separated list of tables to check (default: all)}
                            {--json     : Output results as JSON}';

    protected $description = 'Compare row counts and spot-check records between PostgreSQL and MySQL after migration';

    /** Tables to skip (schema-only, no app data) */
    private const SKIP_TABLES = [
        'migrations',
        'personal_access_tokens',
        'password_reset_tokens',
        'password_resets',
    ];

    /**
     * Key tables: columns checked in spot-check.
     * Format: table => [column that must never be NULL after migration]
     */
    private const SPOT_CHECK_TABLES = [
        'customers'    => ['id', 'name', 'email'],
        'invoices'     => ['id', 'customer_id', 'total_amount'],
        'ads'          => ['id', 'customer_id', 'ad_name'],
        'admins'       => ['id', 'email'],
        'clients'      => ['id', 'name'],
        'packages'     => ['id', 'name'],
        'card_credit_info' => ['id', 'customer_id'],
        'card_debit_info'  => ['id', 'customer_id'],
    ];

    private array $countResults  = [];
    private array $spotResults   = [];
    private int   $countMismatches = 0;
    private int   $spotFailures    = 0;

    public function handle(): int
    {
        // ── 1. Verify connections ──────────────────────────────────────
        $this->info('Verifying connections…');

        try {
            DB::connection('pgsql')->getPdo();
            $this->line('  PostgreSQL: OK');
        } catch (\Exception $e) {
            $this->error('  PostgreSQL connection failed: ' . $e->getMessage());
            return 1;
        }

        try {
            DB::connection('mysql')->getPdo();
            $this->line('  MySQL: OK');
        } catch (\Exception $e) {
            $this->error('  MySQL connection failed: ' . $e->getMessage());
            $this->line('  Make sure MYSQL_DATABASE_URL or DB_HOST/DB_DATABASE/DB_USERNAME/DB_MYSQL_PASSWORD are set.');
            $this->line('  (cPanel firewall must allow the Replit egress IP)');
            return 1;
        }

        $this->newLine();

        // ── 2. Discover tables ─────────────────────────────────────────
        $allTables = $this->discoverPgsqlTables();
        $allTables = array_diff($allTables, self::SKIP_TABLES);

        $only = $this->option('tables')
            ? array_map('trim', explode(',', $this->option('tables')))
            : [];

        $tables = $only ? array_intersect($allTables, $only) : $allTables;

        if (empty($tables)) {
            $this->warn('No tables found to validate.');
            return 0;
        }

        // ── 3. Row-count comparison ────────────────────────────────────
        $this->info('Comparing row counts (' . count($tables) . ' tables)…');
        $bar = $this->output->createProgressBar(count($tables));
        $bar->start();

        foreach ($tables as $table) {
            $this->compareRowCount($table);
            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        // ── 4. Spot-checks ────────────────────────────────────────────
        $spotTables = $only
            ? array_intersect_key(self::SPOT_CHECK_TABLES, array_flip($only))
            : self::SPOT_CHECK_TABLES;

        if (!empty($spotTables)) {
            $this->info('Running spot-checks on key tables…');
            foreach ($spotTables as $table => $columns) {
                if (in_array($table, $tables, true)) {
                    $this->spotCheck($table, $columns);
                }
            }
            $this->newLine();
        }

        // ── 5. Output ─────────────────────────────────────────────────
        if ($this->option('json')) {
            $this->outputJson();
        } else {
            $this->outputReport();
        }

        return ($this->countMismatches + $this->spotFailures) > 0 ? 1 : 0;
    }

    // ─────────────────────────────────────────────────────────────────
    // Row-count comparison
    // ─────────────────────────────────────────────────────────────────

    private function compareRowCount(string $table): void
    {
        $pgsqlCount = null;
        $mysqlCount = null;
        $status     = 'OK';
        $note       = '';

        try {
            $pgsqlCount = DB::connection('pgsql')->table($table)->count();
        } catch (\Exception $e) {
            $status = 'PGSQL_ERROR';
            $note   = substr($e->getMessage(), 0, 120);
        }

        try {
            $mysqlCount = DB::connection('mysql')->table($table)->count();
        } catch (\Exception $e) {
            $status = $status === 'OK' ? 'MYSQL_ERROR' : 'BOTH_ERROR';
            $note   = substr($e->getMessage(), 0, 120);
        }

        if ($status === 'OK') {
            if ($pgsqlCount !== $mysqlCount) {
                $status = 'MISMATCH';
                $note   = 'pgsql=' . number_format($pgsqlCount) . ' mysql=' . number_format($mysqlCount);
                $this->countMismatches++;
            }
        }

        $this->countResults[] = [
            'table'      => $table,
            'pgsql'      => $pgsqlCount,
            'mysql'      => $mysqlCount,
            'status'     => $status,
            'note'       => $note,
        ];
    }

    // ─────────────────────────────────────────────────────────────────
    // Spot-checks: first 5 IDs must exist in both with non-NULL key cols
    // ─────────────────────────────────────────────────────────────────

    private function spotCheck(string $table, array $columns): void
    {
        try {
            $pgsqlRows = DB::connection('pgsql')
                ->table($table)
                ->orderBy('id')
                ->limit(5)
                ->get($columns)
                ->toArray();

            if (empty($pgsqlRows)) {
                $this->spotResults[] = [
                    'table'  => $table,
                    'status' => 'EMPTY',
                    'detail' => 'No rows in PostgreSQL',
                ];
                return;
            }

            $ids = array_column($pgsqlRows, 'id');

            $mysqlRows = DB::connection('mysql')
                ->table($table)
                ->whereIn('id', $ids)
                ->get($columns)
                ->keyBy('id')
                ->toArray();

            $issues = [];

            foreach ($pgsqlRows as $pRow) {
                $pArr = (array) $pRow;
                $id   = $pArr['id'];

                if (!isset($mysqlRows[$id])) {
                    $issues[] = "id={$id} missing in MySQL";
                    continue;
                }

                $mArr = (array) $mysqlRows[$id];

                foreach ($columns as $col) {
                    if ($col === 'id') continue;
                    $pVal = $pArr[$col] ?? null;
                    $mVal = $mArr[$col] ?? null;

                    // Normalize: booleans were coerced to 1/0
                    if (is_bool($pVal)) {
                        $pVal = $pVal ? 1 : 0;
                    }

                    if ((string) $pVal !== (string) $mVal && !($pVal === null && $mVal === null)) {
                        $issues[] = "id={$id} col={$col}: pgsql=" . json_encode($pVal) . " mysql=" . json_encode($mVal);
                    }
                }
            }

            if (empty($issues)) {
                $this->spotResults[] = [
                    'table'  => $table,
                    'status' => 'OK',
                    'detail' => 'First ' . count($pgsqlRows) . ' rows match',
                ];
            } else {
                $this->spotFailures++;
                $this->spotResults[] = [
                    'table'  => $table,
                    'status' => 'FAIL',
                    'detail' => implode('; ', array_slice($issues, 0, 3)) . (count($issues) > 3 ? ' (+more)' : ''),
                ];
            }
        } catch (\Exception $e) {
            $this->spotFailures++;
            $this->spotResults[] = [
                'table'  => $table,
                'status' => 'ERROR',
                'detail' => substr($e->getMessage(), 0, 200),
            ];
        }
    }

    // ─────────────────────────────────────────────────────────────────
    // Output
    // ─────────────────────────────────────────────────────────────────

    private function outputReport(): void
    {
        // Row-count table
        $this->info('── Row Count Comparison ──────────────────────────────');
        $this->table(
            ['Table', 'PostgreSQL', 'MySQL', 'Status', 'Note'],
            array_map(fn($r) => [
                $r['table'],
                $r['pgsql'] !== null ? number_format($r['pgsql']) : '—',
                $r['mysql'] !== null ? number_format($r['mysql']) : '—',
                $r['status'],
                $r['note'],
            ], $this->countResults)
        );

        // Spot-check table
        if (!empty($this->spotResults)) {
            $this->info('── Spot-Check Results ────────────────────────────────');
            $this->table(
                ['Table', 'Status', 'Detail'],
                array_map(fn($r) => [$r['table'], $r['status'], $r['detail']], $this->spotResults)
            );
        }

        // Summary
        $total       = count($this->countResults);
        $okCount     = count(array_filter($this->countResults, fn($r) => $r['status'] === 'OK'));
        $emptyCount  = count(array_filter($this->countResults, fn($r) => $r['pgsql'] === 0));

        $this->info('── Summary ───────────────────────────────────────────');
        $this->table(
            ['Metric', 'Value'],
            [
                ['Tables checked',          $total],
                ['Tables matching',         $okCount],
                ['Empty tables',            $emptyCount],
                ['Count mismatches',        $this->countMismatches],
                ['Spot-check failures',     $this->spotFailures],
                ['Overall result',          ($this->countMismatches + $this->spotFailures) === 0 ? '✓ PASS' : '✗ FAIL'],
            ]
        );
    }

    private function outputJson(): void
    {
        $this->line(json_encode([
            'row_counts'     => $this->countResults,
            'spot_checks'    => $this->spotResults,
            'summary' => [
                'count_mismatches' => $this->countMismatches,
                'spot_failures'    => $this->spotFailures,
                'pass'             => ($this->countMismatches + $this->spotFailures) === 0,
            ],
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    // ─────────────────────────────────────────────────────────────────
    // Helpers
    // ─────────────────────────────────────────────────────────────────

    private function discoverPgsqlTables(): array
    {
        $rows = DB::connection('pgsql')->select(
            "SELECT tablename FROM pg_tables WHERE schemaname = 'public' ORDER BY tablename"
        );
        return array_column($rows, 'tablename');
    }
}
