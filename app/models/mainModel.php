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

    protected function executeQuery($query)
    {
        $sql = $this->connect()->prepare($query);
        $sql->execute();
        return $sql;
    }

    public function cleanString($string)
    {
        $words = ["<script>", "</script>", "<script src", "<script type=", "SELECT * FROM", "SELECT ", " SELECT ", "DELETE FROM", "INSERT INTO", "DROP TABLE", "DROP DATABASE", "TRUNCATE TABLE", "SHOW TABLES", "SHOW DATABASES", "<?php", "?>", "--", "^", "<", ">", "==", "=", ";", "::"];

        $string = trim($string);
        $string = stripslashes($string);

        foreach ($words as $word) {
            $string = str_ireplace($word, "", $string);
        }

        $string = trim($string);
        $string = stripslashes($string);

        return $string;
    }
}
