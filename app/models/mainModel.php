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

    protected function deleteRegister(string $table, string $field, int $id)
    {
        $sql = $this->connect()->prepare("DELETE FROM $table WHERE $field = :id");
        $sql->bindParam(":id", $id);
        $sql->execute();
        return $sql;
    }

    protected function tablesPaginator(int $page, int $numPages, string $url, int $buttons)
    {
        $table = '<nav class="pagination is-centered is-rounded" role="navigation" aria-label="pagination">';

        if ($page <= 1) {
            $table .= '
            <a class="pagination-previous is-disabled" disabled >Anterior</a>
            <ul class="pagination-list">
            ';
        } else {
            $table .= '
            <a class="pagination-previous" href="' . $url . ($page - 1) . '/">Anterior</a>
            <ul class="pagination-list">
                <li><a class="pagination-link" href="' . $url . '1/">1</a></li>
                <li><span class="pagination-ellipsis">&hellip;</span></li>
            ';
        }

        $ci = 0;
        for ($i = $page; $i <= $numPages; $i++) {

            if ($ci >= $buttons) {
                break;
            }

            if ($page == $i) {
                $table .= '<li><a class="pagination-link is-current" href="' . $url . $i . '/">' . $i . '</a></li>';
            } else {
                $table .= '<li><a class="pagination-link" href="' . $url . $i . '/">' . $i . '</a></li>';
            }

            $ci++;
        }

        if ($page == $numPages) {
            $table .= '
            </ul>
            <a class="pagination-next is-disabled" disabled >Siguiente</a>
            ';
        } else {
            $table .= '
                <li><span class="pagination-ellipsis">&hellip;</span></li>
                <li><a class="pagination-link" href="' . $url . $numPages . '/">' . $numPages . '</a></li>
            </ul>
            <a class="pagination-next" href="' . $url . ($page + 1) . '/">Siguiente</a>
	        ';
        }

        $table .= '</nav>';
        return $table;
    }
}
