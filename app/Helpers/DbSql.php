<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;

class DbSql
{
    public static function isPgsql(): bool
    {
        return DB::getDriverName() === 'pgsql';
    }

    public static function dateFormat(string $column, string $mysqlFormat): string
    {
        if (static::isPgsql()) {
            $pg = str_replace(['%Y', '%m', '%d'], ['YYYY', 'MM', 'DD'], $mysqlFormat);
            return "TO_CHAR($column, '$pg')";
        }
        return "DATE_FORMAT($column, '$mysqlFormat')";
    }

    public static function currentDate(): string
    {
        return static::isPgsql() ? 'CURRENT_DATE' : 'CURDATE()';
    }

    public static function dateOf(string $column): string
    {
        return static::isPgsql() ? "$column::date" : "DATE($column)";
    }

    public static function dateDiff(string $end, string $start): string
    {
        return static::isPgsql() ? "($end - $start)" : "DATEDIFF($end, $start)";
    }

    public static function sumCol(string $column): string
    {
        return static::isPgsql() ? "SUM(\"$column\")" : "SUM($column)";
    }

    /**
     * Returns the column reference with proper quoting.
     * pgsql: "USD" — mysql: USD
     */
    public static function colRef(string $column): string
    {
        return static::isPgsql() ? "\"$column\"" : $column;
    }

    /**
     * SUM(COALESCE(col, 0)) — works on both drivers.
     */
    public static function sumCoalesce(string $column, mixed $default = 0): string
    {
        $col = static::colRef($column);
        return "SUM(COALESCE($col, $default))";
    }

    public static function regexpReplace(string $column, string $pattern, string $replacement = ''): string
    {
        if (static::isPgsql()) {
            return "regexp_replace(\"$column\", '$pattern', '$replacement')";
        }
        return "REGEXP_REPLACE($column, '$pattern', '$replacement')";
    }

    /**
     * Wrap an expression with an alias, quoting the alias on PostgreSQL
     * so camelCase aliases are preserved (e.g. "totalUSD" not "totalusd").
     */
    public static function as(string $expression, string $alias): string
    {
        if (static::isPgsql()) {
            return $expression . ' as "' . $alias . '"';
        }
        return $expression . ' as ' . $alias;
    }
}
