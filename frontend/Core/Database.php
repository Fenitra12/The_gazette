<?php

namespace App\Core;

use PDO;
use PDOException;

class Database
{
    private static $connection = null;

    public static function init($config)
    {
        if (self::$connection === null) {
            try {
                $dsn = sprintf(
                    "pgsql:host=%s;port=%s;dbname=%s",
                    $config['host'],
                    $config['port'],
                    $config['database']
                );
                
                self::$connection = new PDO($dsn, $config['username'], $config['password'], [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]);
            } catch (PDOException $e) {
                die("Erreur de connexion a la base de donnees : " . $e->getMessage());
            }
        }
    }

    public static function getConnection()
    {
        return self::$connection;
    }
}
