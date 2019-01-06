<?php
require 'dataBase.php';

class pgsqlConnection {

    function connection() {
        $dataPostgre = new dataBase('pgsql', 'DATABASE_URL_LOCAL');

        return $dataPostgre->connect();
    }
}
?>