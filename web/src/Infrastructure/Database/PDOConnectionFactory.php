<?php
declare(strict_types=1);

namespace HelloFresh\Infrastructure\Database;

use PDO;

final class PDOConnectionFactory
{
    public static function build(
        string $dbType,
        string $dbName,
        string $host,
        string $user,
        string $password,
        string $defaultSchema = ''
    ): PDO
    {
        $dsn = sprintf('%s:host=%s;dbname=%s', $dbType, $host, $dbName);
        $pdo = new PDO($dsn, $user, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        if (!empty($defaultSchema)) {
            $pdo->exec(sprintf("SET search_path TO %s", $defaultSchema));
        }

        return $pdo;
    }
}
