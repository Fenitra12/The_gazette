<?php
declare(strict_types=1);

namespace BackOffice\Core;

use PDO;
use PDOException;

final class Database
{
    private static ?PDO $pdo = null;

    /**
     * @param array{driver:string,host:string,port:string,database:string,username:string,password:string} $config
     */
    public static function init(array $config): void
    {
        if (self::$pdo !== null) {
            return;
        }

        try {
            $driver = $config['driver'] ?? 'pgsql';
            if ($driver !== 'pgsql') {
                throw new PDOException('Unsupported DB_DRIVER (expected pgsql)');
            }

            $dsn = sprintf(
                'pgsql:host=%s;port=%s;dbname=%s',
                $config['host'],
                $config['port'],
                $config['database']
            );

            self::$pdo = new PDO($dsn, $config['username'], $config['password'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        } catch (PDOException $e) {
            http_response_code(500);
            echo 'Database connection error.';
            exit;
        }
    }

    public static function pdo(): PDO
    {
        if (!self::$pdo) {
            throw new PDOException('Database not initialized');
        }
        return self::$pdo;
    }
}

