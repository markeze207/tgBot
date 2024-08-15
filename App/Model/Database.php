<?php

namespace App\Model;

use PDO;

class Database
{
    public static function getConnection(): PDO
    {
        $dsn = "mysql:host=localhost;dbname=" . $_ENV['DB_NAME'];
        return new PDO($dsn, $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD']);
    }
}
