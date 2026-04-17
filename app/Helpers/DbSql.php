<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;

/**
 * Driver-aware SQL expression helpers.
 * Generates correct SQL for PostgreSQL (pgsql) and MySQL (mysql/mariadb).
 */
class DbSql
{
    public static function isPgsql(): bool
    {
        return DB::getDriverName() === 'pgsql';
    }

    /**
     * Date format expression: TO_CHAR (pgsql) or DATE_FORMAT (mysql).
     * Use MySQL-style format string e.g. '%Y-%m' or '%Y-%m-%d'.
     */
    public static function dateFormat(string $column, string $mysqlFormat): string
    {
        if (static::isPgsql()) {
            $pg = str_replace(['%Y', '%m', '%d'], ['YYYY', 'MM', 'DD'], $mysqlFormat);
            return "TO_CHAR($column, '$pg')";
        }
        return "DATE_FORMAT($column, '$mysqlFormat')";
    }

    /** CURRENT_DATE (pgsql) or CURDATE() (mysql). */
    public static function currentDate(): string
    {
        return static::isPgsql() ? 'CURRENT_DATE' : 'CURDATE()';
    }

    /** Cast to date: col::date (pgsql) or DATE(col) (mysql). */
    public static function dateOf(string $column): string
    {
        return static::isPgsql() ? "$column::date" : "DATE($column)";
    }

    /** Date difference: (end - start) (pgsql) or DATEDIFF(end, start) (mysql). */
    public static function dateDiff(string $end, string $start): string
    {
        return static::isPgsql() ? "($end - $start)" : "DATEDIFF($end, $start)";
    }

    /** SUM with proper column quoting: SUM("USD") (pgsql) or SUM(USD) (mysql). */
    public static function sumCol(string $column): string
    {
        return static::isPgsql() ? "SUM(\"$column\")" : "SUM($column)";
    }

    /** Column reference with proper quoting: "USD" (pgsql) or USD (mysql). */
    public static function colRef(string $column): string
    {
        return static::isPgsql() ? "\"$column\"" : $column;
    }

    /** SUM(COALESCE(col, default)) compatible with both drivers. */
    public static function sumCoalesce(string $column, int $default = 0): string
    {
        $col = static::colRef($column);
        return "SUM(COALESCE($col, $default))";
    }

    /** REGEXP_REPLACE compatible version (both drivers support this). */
    public static function regexpReplace(string $column, string $pattern, string $replacement = ''): string
    {
        if (static::isPgsql()) {
            return "regexp_replace(\"$column\", '$pattern', '$replacement')";
        }
        return "REGEXP_REPLACE($column, '$pattern', '$replacement')";
    }

    /**
     * Extract the portion of a column after the last occurrence of a delimiter.
     * Uses regexp_replace on pgsql and SUBSTRING_INDEX on mysql (MySQL 5.7+ compatible).
     */
    public static function extractAfterLastDelimiter(string $column, string $delimiter): string
    {
        if (static::isPgsql()) {
            return "regexp_replace(\"$column\", '^.*{$delimiter}', '')";
        }
        return "SUBSTRING_INDEX($column, '$delimiter', -1)";
    }

    /** YEAR(col) for mysql, EXTRACT(YEAR FROM col) for pgsql. */
    public static function year(string $column): string
    {
        return static::isPgsql() ? "EXTRACT(YEAR FROM $column)" : "YEAR($column)";
    }

    /** MONTH(col) for mysql, EXTRACT(MONTH FROM col) for pgsql. */
    public static function month(string $column): string
    {
        return static::isPgsql() ? "EXTRACT(MONTH FROM $column)" : "MONTH($column)";
    }

    /**
     * Wrap expression with an alias, quoting the alias on PostgreSQL
     * to preserve camelCase (e.g. totalUSD not totalusd).
     */
    public static function alias(string $expression, string $name): string
    {
        if (static::isPgsql()) {
            return $expression . ' as "' . $name . '"';
        }
        return $expression . ' as ' . $name;
    }
}
