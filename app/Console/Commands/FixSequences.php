<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * Resets all PostgreSQL sequences in the public schema so that the next
 * auto-increment value is max(column) + 1.
 *
 * Run this after any bulk import that inserts rows with explicit IDs,
 * otherwise the sequence may still be at 1 and the next INSERT will fail
 * with a "duplicate key" error.
 *
 * Usage:
 *   php artisan db:fix-sequences                   # reset all sequences
 *   php artisan db:fix-sequences --tables=users,ads # reset specific tables
 *   php artisan db:fix-sequences --dry-run          # preview without applying
 *
 * This command handles both SERIAL/BIGSERIAL columns (owned via pg_depend
 * deptype='a') and GENERATED AS IDENTITY columns (deptype='i').
 */
class FixSequences extends Command
{
    protected $signature = 'db:fix-sequences
                            {--tables= : Comma-separated list of tables to fix (default: all)}
                            {--dry-run : Show what would be changed without applying}';

    protected $description = 'Reset PostgreSQL sequences to max(id)+1 so new inserts do not clash with imported data';

    public function handle(): int
    {
        $dryRun = $this->option('dry-run');
        $onlyTables = $this->option('tables')
            ? array_map('trim', explode(',', $this->option('tables')))
            : [];

        if ($dryRun) {
            $this->warn('DRY RUN — no sequences will be changed.');
        }

        $sequences = $this->discoverSequences();

        if (empty($sequences)) {
            $this->warn('No sequences found in the public schema.');
            return 0;
        }

        if (!empty($onlyTables)) {
            $sequences = array_filter(
                $sequences,
                fn($s) => in_array($s->table_name, $onlyTables)
            );
        }

        $rows = [];
        $fixed = 0;

        foreach ($sequences as $seq) {
            $seqName  = $seq->sequence_name;
            $table    = $seq->table_name;
            $column   = $seq->column_name;

            try {
                $maxId = DB::table($table)->max($column) ?? 0;
                $nextVal = max((int) $maxId + 1, 1);

                if (!$dryRun) {
                    DB::statement("SELECT setval(?, ?, false)", [$seqName, $nextVal]);
                }

                $rows[] = [$table, $column, $seqName, $maxId, $nextVal];
                $fixed++;
            } catch (\Exception $e) {
                $this->error("Failed to fix sequence {$seqName}: " . $e->getMessage());
            }
        }

        $this->table(
            ['Table', 'Column', 'Sequence', 'Current Max', 'Next Value'],
            $rows
        );

        $action = $dryRun ? 'Would fix' : 'Fixed';
        $this->info("{$action} {$fixed} sequence(s).");

        return 0;
    }

    /**
     * Discover all sequences in the public schema along with their
     * associated table and column.
     *
     * Covers two PostgreSQL ownership patterns:
     *   deptype = 'a'  →  SERIAL/BIGSERIAL columns (sequence owned via OWNED BY)
     *   deptype = 'i'  →  GENERATED AS IDENTITY columns (internally owned sequence)
     */
    private function discoverSequences(): array
    {
        return DB::select("
            SELECT
                seq.relname          AS sequence_name,
                tab.relname          AS table_name,
                attr.attname         AS column_name
            FROM pg_class seq
            JOIN pg_depend dep
                ON dep.objid = seq.oid
               AND dep.deptype IN ('a', 'i')
            JOIN pg_class tab
                ON tab.oid = dep.refobjid
            JOIN pg_attribute attr
                ON attr.attrelid = dep.refobjid
               AND attr.attnum   = dep.refobjsubid
            WHERE seq.relkind = 'S'
              AND tab.relnamespace = (
                    SELECT oid FROM pg_namespace WHERE nspname = 'public'
                  )
            ORDER BY tab.relname, attr.attname
        ");
    }
}
