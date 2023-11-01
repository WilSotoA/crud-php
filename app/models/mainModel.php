<?php

namespace app\models;

use PDO;

if (file_exists(__DIR__ . "/../../config/server.php")) {
    require_once __DIR__ . "/../../config/server.php";
}

class mainModel
{
    private $server = DB_SERVER;
    private $db = DB_NAME;
    private $user = DB_USER;
    private $pass = DB_PASSWORD;

    protected function connect()
    {
        $connection = new PDO(
            "mysql:host=" . $this->server . ";
            dbname=" . $this->db,
            $this->user,
            $this->pass
        );
        $connection->exec("SET CHARACTER SET utf8");
        return $connection;
    }
}
