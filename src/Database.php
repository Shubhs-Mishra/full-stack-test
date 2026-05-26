<?php

class Database
{
    private static ?PDO $connection = null;

    private function __construct()
    {
    }

    public static function getConnection(): PDO
    {
        if (self::$connection === null) {
            self::$connection = self::createConnection();
        }

        return self::$connection;
    }

    private static function createConnection(): PDO
    {
        require_once __DIR__ . '/../config.php';

        try {
            $pdo = new PDO(getDatabaseDsn(), getDatabaseUser(), getDatabasePassword(), [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        } catch (PDOException $error) {
            throw new RuntimeException('Could not connect to the database: ' . $error->getMessage());
        }

        return $pdo;
    }
}
