<?php

class Database
{
    private const HOST = '';
    private const DB_NAME = '';
    private const USERNAME = '';
    private const PASSWORD = '';

    public static function getConnection()
    {
        try {
            $host = self::HOST;
            $db_name = self::DB_NAME;
            $connection = new PDO("mysql:host=$host; dbname=$db_name", self::USERNAME, self::PASSWORD);
            $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $connection;

        } catch (PDOException $e) {
            echo 'ERR connection db: ' . $e->getMessage();
            return null;
        }
    }
}

?>