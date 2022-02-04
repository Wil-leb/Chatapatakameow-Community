<?php

namespace App\core;

use \PDO;

class Connect {
    const HOST = "127.0.0.1";
    const DB_NAME = "willeb_cl";
    const USER = "root";
    // const PASSWORD = "a79d0ca0b9a30b6f484ffde6adf777db";
    

//*****Allowing the connection*****//
    public function connection() {
        // $pdo = new PDO("mysql:host=".self::HOST.";dbname=".self::DB_NAME, self::USER, self::PASSWORD);
        $pdo = new PDO("mysql:host=".self::HOST.";dbname=".self::DB_NAME, self::USER);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->exec("SET NAMES UTF8");
        return $pdo;
    }

//*****END OF THE CLASS*****//
}
