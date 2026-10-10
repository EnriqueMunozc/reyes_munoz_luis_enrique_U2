<?php

declare(strict_types=1);

$host = getenv('DB_HOST') ?: 'sqlserver';
$port = getenv('DB_PORT') ?: '1433';
$database = getenv('DB_DATABASE') ?: 'redline';
$username = getenv('DB_USERNAME') ?: 'sa';
$password = getenv('DB_PASSWORD') ?: '';

if (! preg_match('/^[A-Za-z0-9_]+$/', $database)) {
    fwrite(STDERR, "El nombre de base de datos Docker no es valido.\n");
    exit(1);
}

$dsn = sprintf(
    'sqlsrv:Server=%s,%s;Database=master;Encrypt=yes;TrustServerCertificate=yes',
    $host,
    $port,
);

for ($attempt = 1; $attempt <= 30; $attempt++) {
    try {
        $connection = new PDO($dsn, $username, $password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        $quotedDatabase = str_replace(']', ']]', $database);
        $connection->exec("IF DB_ID(N'{$database}') IS NULL EXEC('CREATE DATABASE [{$quotedDatabase}]')");
        fwrite(STDOUT, "Base de datos Docker comprobada.\n");
        exit(0);
    } catch (PDOException $exception) {
        if ($attempt === 30) {
            fwrite(STDERR, "No se pudo preparar la base de datos Docker.\n");
            exit(1);
        }

        sleep(2);
    }
}
