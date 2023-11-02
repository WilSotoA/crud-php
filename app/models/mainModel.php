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

    protected function executeQuery(string $query)
    {
        $sql = $this->connect()->prepare($query);
        $sql->execute();
        return $sql;
    }

    public function cleanString(string $string)
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

    protected function verifyData(string $filter, string $string)
    {
        $result = (preg_match("/^.$filter.$/", $string)) ? true : false;
        return $result;
    }

    protected function saveData(string $table, array $data)
    {
        $query = "INSERT INTO $table (";
        $C = 0;

        foreach ($data as $key) {
            if ($C >= 1) $query .= ",";
            $query .= $key['name'];
            $C++;
        }

        $query .= ") VALUES (";
        $C = 0;

        foreach ($data as $key) {
            if ($C >= 1) $query .= ",";
            $query .= $key['field'];
            $C++;
        }

        $query .= ")";
        $sql = $this->connect()->prepare($query);

        foreach ($data as $key) {
            $sql->bindParam($key['field'], $key['value']);
        }

        $sql->execute();
        return $sql;
    }

    public function selectData(string $type, string $table, string $field, int $id)
    {
        $type = $this->cleanString($type);
        $table = $this->cleanString($table);
        $field = $this->cleanString($field);
        $id = $this->cleanString($id);

        if ($type == 'Unique') {
            $sql = $this->connect()->prepare("SELECT * FROM $table WHERE $field = :ID");
            $sql->bindParam(":ID", $id);
        } elseif ($type == 'Normal') {
            $sql = $this->connect()->prepare("SELECT $field FROM $table");
        }

        $sql->execute();
        return $sql;
    }

    protected function updateData(string $table, array $data, string $condition)
    {
        $query = "UPDATE $table SET ";
        $C = 0;

        foreach ($data as $key) {
            if ($C >= 1) $query .= ",";
            $query .= $key['name'] . " = " . $key['field'];
            $C++;
        }

        $query .= " WHERE " . $condition['name'] . " = " . $condition['field'];
        $sql = $this->connect()->prepare($query);

        foreach ($data as $key) {
            $sql->bindParam($key['field'], $key['value']);
        }

        $sql->bindParam($condition['field'], $condition['value']);
        $sql->execute();
        return $sql;
    }
}
