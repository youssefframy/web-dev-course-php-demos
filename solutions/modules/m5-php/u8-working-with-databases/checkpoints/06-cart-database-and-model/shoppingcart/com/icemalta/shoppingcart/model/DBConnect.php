<?php
namespace com\icemalta\shoppingcart\model;

use PDO;
use RuntimeException;

class DBConnect
{
    private static ?DBConnect $singleton = null;
    private PDO $dbh;

    private function __construct()
    {
        // The credentials live in .env, which is never committed.
        // __DIR__ is .../com/icemalta/shoppingcart/model, so four levels up is the app root.
        $env = @parse_ini_file(dirname(__DIR__, 4) . '/.env');
        if ($env === false) {
            throw new RuntimeException('Missing .env file. Copy .env.example to .env and fill it in.');
        }

        $this->dbh = new PDO(
            "mysql:host={$env['DB_HOST']};dbname={$env['DB_NAME']};charset=utf8mb4",
            $env['DB_USER'],
            $env['DB_PASS'],
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_PERSISTENT => true,
            ]
        );
    }

    public static function getInstance(): DBConnect
    {
        self::$singleton = self::$singleton ?? new DBConnect();
        return self::$singleton;
    }

    public function getConnection(): PDO
    {
        return $this->dbh;
    }

    // A PDO connection cannot be serialized, so the session gets nothing.
    // Classes that hold a DBConnect ask for the instance again when they are unserialized.
    public function __serialize(): array
    {
        return [];
    }

    public function __unserialize(array $data): void
    {
    }
}
