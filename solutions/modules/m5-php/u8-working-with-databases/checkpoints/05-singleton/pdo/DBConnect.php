<?php
class DBConnect
{
    private static ?DBConnect $singleton = null;
    private PDO $dbh;

    // Private: no other file can write `new DBConnect()`.
    private function __construct()
    {
        $host = getenv('DB_HOST') ?: '127.0.0.1';
        $this->dbh = new PDO(
            "mysql:host=$host;dbname=shoppingcart;charset=utf8mb4",
            'root',
            'root',
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_PERSISTENT => true]
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
}
