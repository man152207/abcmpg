<?php
/**
 * Quick MySQL connection verifier for cPanel database at 190.92.174.35.
 *
 * Usage — set env vars then run:
 *   DB_HOST=190.92.174.35 DB_DATABASE=mpgcomnp_wp146 \
 *   DB_USERNAME=mpgcomnp_wp146 DB_MYSQL_PASSWORD=secret \
 *   php scripts/verify-mysql.php
 *
 * Or inside the deployed app:
 *   php artisan tinker  ->  DB::connection('mysql')->getPdo();
 */

$host = getenv('DB_HOST')           ?: '190.92.174.35';
$port = getenv('DB_PORT')           ?: '3306';
$db   = getenv('DB_DATABASE')       ?: 'mpgcomnp_wp146';
$user = getenv('DB_USERNAME')       ?: 'mpgcomnp_wp146';
$pass = getenv('DB_MYSQL_PASSWORD') ?: getenv('DB_PASSWORD') ?: '';

echo "Connecting to MySQL: {$user}@{$host}:{$port}/{$db}\n";

try {
    $dsn = "mysql:host={$host};port={$port};dbname={$db};charset=utf8mb4";
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_TIMEOUT => 5,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);

    $version = $pdo->query('SELECT VERSION()')->fetchColumn();
    echo "SUCCESS — connected. MySQL server version: {$version}\n";
} catch (PDOException $e) {
    echo "FAILED — " . $e->getMessage() . "\n";
    exit(1);
}

// Table listing is best-effort; restricted grants may deny SHOW TABLES
// even when the connection and basic queries work fine.
try {
    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    $preview = implode(', ', array_slice($tables, 0, 10));
    $extra   = count($tables) > 10 ? ' ...' : '';
    echo "Tables (" . count($tables) . "): {$preview}{$extra}\n";
} catch (PDOException $e) {
    echo "Note: SHOW TABLES not permitted ({$e->getMessage()}) — connection is still valid.\n";
}
