<?php


namespace app\components;


use PDO;

class Database
{
    private PDO $connection;

    public function __construct(string $host, string $dbName, string $user, string $password)
    {
        $this->connection = new PDO("mysql:host={$host};dbname={$dbName};charset=utf8", $user, $password);
    }
}