<?php

namespace Commercial\Framework\Database;

class Connection
{
    protected static $instance = null;

    public static function open()
    {
        static::$instance = static::$instance ?: static::createConnection();
        return static::$instance;
    }

    public static function beginTransaction()
    {
        static::open()->beginTransaction();
    }

    public static function commit()
    {
        if (static::isInTransaction()) {
            static::open()->commit();
        }
    }

    public static function rollBack()
    {
        if (static::isInTransaction()) {
            static::open()->rollBack();
        }
    }

    public static function isInTransaction()
    {
        return static::open()->inTransaction();
    }

    public static function execute($query, $binds)
    {
        $statement = static::$instance->prepare($query);
        foreach ($binds as $key => $value) {
            $statement->bindValue(":{$key}", $value);
        }
        $statement->execute();
        return $statement;
    }

    private static function createConnection()
    {
        $host = $_ENV["DB_HOST"] ?? "localhost";
        $port = $_ENV["DB_PORT"] ?? "3306";
        $name = $_ENV["DB_NAME"] ?? "mydb";
        $user = $_ENV["DB_USER"] ?? "root";
        $pass = $_ENV["DB_PASS"] ?? "";

        return new \PDO(
            "mysql:host={$host};port={$port};dbname={$name};charset=utf8mb4",
            $user,
            $pass,
            [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_PERSISTENT => false,
                \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8mb4' COLLATE 'utf8mb4_unicode_ci'"
            ]
        );
    }
}
