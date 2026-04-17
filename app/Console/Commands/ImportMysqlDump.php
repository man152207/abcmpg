<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportMysqlDump extends Command
{
    protected $signature = 'db:import-mysql {file : Path to the MySQL dump SQL file}
                            {--dry-run : Parse only, do not insert data}
                            {--tables= : Comma-separated list of tables to import (default: all)}
                            {--clear : Truncate target tables before importing}';

    protected $description = 'Import a MySQL dump file into the PostgreSQL database (data only, schema already exists)';

    private int $totalRows = 0;
    private array $errors = [];
    /** @var array<string, array<string, string>> table → [col → pg_type] */
    private array $tableSchemas = [];

    public function handle(): int
    {
        $file = $this->argument('file');
        $dryRun = $this->option('dry-run');
        $clear  = $this->option('clear');
        $onlyTables = $this->option('tables')
            ? array_map('trim', explode(',', $this->option('tables')))
            : [];

        if (!file_exists($file)) {
            $this->error("File not found: {$file}");
            return 1;
        }

        $this->info("Reading MySQL dump: {$file}");
        if ($dryRun) {
            $this->warn('DRY RUN mode — no data will be inserted.');
        }

        $sql = file_get_contents($file);
        $inserts = $this->extractInserts($sql, $onlyTables);

        if (empty($inserts)) {
            $this->warn('No INSERT statements found in the dump file.');
            return 0;
        }

        // Pre-load PostgreSQL column type info for all target tables
        if (!$dryRun) {
            $this->loadTableSchemas(array_keys($inserts));
        }

        $this->info('Found INSERT statements for tables: ' . implode(', ', array_keys($inserts)));

        if ($clear && !$dryRun) {
            $this->clearTables(array_keys($inserts));
        }

        $totalStatements = array_sum(array_map('count', $inserts));
        $bar = $this->output->createProgressBar($totalStatements);
        $bar->start();

        foreach ($inserts as $table => $statements) {
            $this->importTable($table, $statements, $dryRun, $bar);
        }

        $bar->finish();
        $this->newLine(2);

        $this->info('Import complete.');
        $this->table(['Metric', 'Value'], [
            ['Tables processed', count($inserts)],
            ['Total rows inserted', $this->totalRows],
            ['Errors', count($this->errors)],
        ]);

        if (!empty($this->errors)) {
            $this->warn('Errors (first 30):');
            foreach (array_slice($this->errors, 0, 30) as $err) {
                $this->line("  - {$err}");
            }
            if (count($this->errors) > 30) {
                $this->line('  ... and ' . (count($this->errors) - 30) . ' more.');
            }
        }

        if (!$dryRun) {
            $this->newLine();
            $this->info('Row counts after import:');
            $rows = [];
            foreach (array_keys($inserts) as $table) {
                try {
                    $count = DB::table($table)->count();
                    $rows[] = [$table, number_format($count)];
                } catch (\Exception $e) {
                    $rows[] = [$table, 'error: ' . $e->getMessage()];
                }
            }
            $this->table(['Table', 'Row Count'], $rows);

            if ($clear) {
                $this->newLine();
                $this->info('Resetting sequences so new inserts do not clash with imported data…');
                $this->call('db:fix-sequences', [
                    '--tables' => implode(',', array_keys($inserts)),
                ]);
            }
        }

        return count($this->errors) > 0 ? 1 : 0;
    }

    private function loadTableSchemas(array $tables): void
    {
        $placeholders = implode(',', array_fill(0, count($tables), '?'));
        $rows = DB::select("
            SELECT table_name, column_name, udt_name
            FROM information_schema.columns
            WHERE table_schema = 'public' AND table_name IN ({$placeholders})
        ", $tables);

        foreach ($rows as $row) {
            $this->tableSchemas[$row->table_name][$row->column_name] = $row->udt_name;
        }
    }

    private function clearTables(array $tables): void
    {
        DB::statement('SET session_replication_role = replica;');
        try {
            foreach ($tables as $table) {
                try {
                    DB::table($table)->truncate();
                } catch (\Exception $e) {
                    // table may not exist
                }
            }
        } finally {
            DB::statement('SET session_replication_role = DEFAULT;');
        }
    }

    private function importTable(string $table, array $statements, bool $dryRun, $bar): void
    {
        if ($dryRun) {
            foreach ($statements as $stmt) {
                $this->totalRows += $this->countRowsInStatement($stmt);
                $bar->advance();
            }
            return;
        }

        DB::statement('SET session_replication_role = replica;');
        try {
            foreach ($statements as $stmt) {
                $pgSql = $this->convertToPostgres($stmt, $table);
                if ($pgSql === null) {
                    $bar->advance();
                    continue;
                }
                try {
                    DB::unprepared($pgSql);
                    $this->totalRows += $this->countRowsInStatement($stmt);
                } catch (\Exception $e) {
                    $this->errors[] = "[{$table}] " . substr($e->getMessage(), 0, 200);
                }
                $bar->advance();
            }
        } finally {
            DB::statement('SET session_replication_role = DEFAULT;');
        }
    }

    private function countRowsInStatement(string $stmt): int
    {
        if (!preg_match('/VALUES\s*(\(.*)/si', $stmt, $m)) {
            return 1;
        }
        $valuesPart = $m[1];
        $depth = 0;
        $rowCount = 0;
        $inString = false;
        $stringChar = '';

        for ($i = 0, $len = strlen($valuesPart); $i < $len; $i++) {
            $ch = $valuesPart[$i];
            if ($inString) {
                if ($ch === '\\') {
                    $i++;
                } elseif ($ch === $stringChar) {
                    $inString = false;
                }
                continue;
            }
            if ($ch === "'" || $ch === '"') {
                $inString = true;
                $stringChar = $ch;
            } elseif ($ch === '(') {
                $depth++;
                if ($depth === 1) {
                    $rowCount++;
                }
            } elseif ($ch === ')') {
                $depth--;
            }
        }
        return max(1, $rowCount);
    }

    /** Tables that must never be imported (Laravel system tables or security-sensitive) */
    private const SKIP_TABLES = [
        'migrations',
        'personal_access_tokens',
        'password_reset_tokens',
        'password_resets',
    ];

    private function extractInserts(string $sql, array $onlyTables): array
    {
        $inserts = [];
        $lines = explode("\n", $sql);
        $buffer = '';

        foreach ($lines as $line) {
            $trimmed = ltrim($line);
            if ($trimmed === '' || str_starts_with($trimmed, '--')) {
                continue;
            }
            if (str_starts_with($trimmed, '/*')) {
                continue;
            }
            if (preg_match('/^(SET\s|USE\s|CREATE\s|ALTER\s|DROP\s|LOCK\s|UNLOCK\s)/i', $trimmed)) {
                continue;
            }

            $buffer .= ' ' . $trimmed;

            if (str_ends_with(rtrim($trimmed), ';')) {
                $stmt = trim($buffer);
                $buffer = '';

                $pos = stripos($stmt, 'INSERT INTO');
                if ($pos !== false) {
                    $stmt = substr($stmt, $pos);
                    $table = $this->extractTableName($stmt);
                    if (
                        $table
                        && !in_array($table, self::SKIP_TABLES, true)
                        && (empty($onlyTables) || in_array($table, $onlyTables))
                    ) {
                        $inserts[$table][] = $stmt;
                    }
                }
            }
        }

        return $inserts;
    }

    private function extractTableName(string $stmt): ?string
    {
        if (preg_match('/INSERT\s+INTO\s+[`"\[]?(\w+)[`"\]]?/iu', $stmt, $m)) {
            return $m[1];
        }
        return null;
    }

    private function convertToPostgres(string $stmt, string $table): ?string
    {
        // Replace MySQL backtick identifiers with double-quotes
        $stmt = str_replace('`', '"', $stmt);

        // MySQL escaped single-quote \' -> ''
        $stmt = str_replace("\\'", "''", $stmt);

        // MySQL escaped double-quote inside strings \" -> " (needed for JSON fields)
        $stmt = str_replace('\\"', '"', $stmt);

        // Handle MySQL escape sequences carefully:
        // \\n (4 chars) in dump = stored \n JSON escape → keep as 2-char \n
        // \\t / \\r same treatment
        // Then remaining \\ → single backslash
        $stmt = str_replace('\\\\n', '\\n', $stmt);
        $stmt = str_replace('\\\\t', '\\t', $stmt);
        $stmt = str_replace('\\\\r', '\\r', $stmt);
        $stmt = str_replace('\\\\', '\\', $stmt);

        // MySQL zero-date literals → NULL
        $stmt = str_replace("'0000-00-00 00:00:00'", 'NULL', $stmt);
        $stmt = str_replace("'0000-00-00'", 'NULL', $stmt);

        // MySQL bit literals b'0' / b'1' → false/true
        $stmt = preg_replace("/\bb'0'/i", 'false', $stmt);
        $stmt = preg_replace("/\bb'1'/i", 'true', $stmt);

        // Remove inline MySQL versioned block comments /*!40101 ... */
        $stmt = preg_replace('/\/\*!\d*\s*(.*?)\s*\*\//s', '$1', $stmt);
        $stmt = preg_replace('/\/\*.*?\*\//s', '', $stmt);

        // Fix integer values for boolean columns
        if (!empty($this->tableSchemas[$table])) {
            $stmt = $this->fixBooleanValues($stmt, $table);
        }

        $stmt = trim($stmt);
        if (!str_ends_with($stmt, ';')) {
            $stmt .= ';';
        }

        return $stmt;
    }

    /**
     * Parse the column list from the INSERT header, then walk through each VALUE tuple
     * replacing raw 0/1 integers at boolean column positions with false/true.
     */
    private function fixBooleanValues(string $stmt, string $table): string
    {
        // Extract column names from INSERT INTO tbl (col1, col2, ...)
        if (!preg_match('/INSERT\s+INTO\s+["`]?\w+["`]?\s*\(([^)]+)\)/i', $stmt, $colMatch)) {
            return $stmt;
        }

        $colNames = array_map(fn($c) => trim($c, ' `"\''), explode(',', $colMatch[1]));
        $schema = $this->tableSchemas[$table] ?? [];

        // Find which positions are boolean
        $boolPositions = [];
        foreach ($colNames as $i => $col) {
            if (($schema[$col] ?? '') === 'bool') {
                $boolPositions[] = $i;
            }
        }

        if (empty($boolPositions)) {
            return $stmt;
        }

        // Locate VALUES keyword and process each tuple
        $valuesPos = stripos($stmt, ' VALUES ');
        if ($valuesPos === false) {
            return $stmt;
        }

        $header = substr($stmt, 0, $valuesPos + 8);
        $rest   = substr($stmt, $valuesPos + 8);

        $rest = $this->replaceBoolInTuples($rest, $boolPositions);
        return $header . $rest;
    }

    /**
     * Walk through comma-separated top-level tuples and replace integer 0/1
     * at the given column positions with false/true.
     */
    private function replaceBoolInTuples(string $valuesPart, array $boolPositions): string
    {
        $result = '';
        $i = 0;
        $len = strlen($valuesPart);

        while ($i < $len) {
            // Find opening paren of a tuple
            while ($i < $len && $valuesPart[$i] !== '(') {
                $result .= $valuesPart[$i++];
            }
            if ($i >= $len) {
                break;
            }

            // Parse the tuple
            $result .= '(';
            $i++; // skip '('
            $colIndex = 0;
            $tupleEnd = false;

            while ($i < $len && !$tupleEnd) {
                // Skip whitespace
                while ($i < $len && $valuesPart[$i] === ' ') {
                    $result .= $valuesPart[$i++];
                }

                // Read a single value
                $value = '';
                if ($i < $len && $valuesPart[$i] === "'") {
                    // String literal
                    $value .= "'";
                    $i++;
                    while ($i < $len) {
                        $ch = $valuesPart[$i];
                        if ($ch === '\\') {
                            $value .= $ch . ($valuesPart[$i + 1] ?? '');
                            $i += 2;
                        } elseif ($ch === "'") {
                            $value .= "'";
                            $i++;
                            break;
                        } else {
                            $value .= $ch;
                            $i++;
                        }
                    }
                } else {
                    // Non-string: read until , or )
                    while ($i < $len && $valuesPart[$i] !== ',' && $valuesPart[$i] !== ')') {
                        $value .= $valuesPart[$i++];
                    }
                }

                $trimmedValue = trim($value);

                // If this column is boolean and value is 0 or 1, convert
                if (in_array($colIndex, $boolPositions) && preg_match('/^[01]$/', $trimmedValue)) {
                    $result .= $trimmedValue === '1' ? 'true' : 'false';
                } else {
                    $result .= $value;
                }

                // Next char should be , or )
                if ($i < $len) {
                    if ($valuesPart[$i] === ',') {
                        $result .= ',';
                        $i++;
                        $colIndex++;
                    } elseif ($valuesPart[$i] === ')') {
                        $result .= ')';
                        $i++;
                        $tupleEnd = true;
                    }
                }
            }
        }

        return $result;
    }
}
