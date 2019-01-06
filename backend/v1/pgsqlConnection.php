<?php
require 'dataBase.php';

class pgsqlConnection {

    function connection() {
<<<<<<< HEAD
        $dataPostgre = new dataBase('pgsql', 'DATABASE_URL');
=======
        $dataPostgre = new dataBase('pgsql', 'DATABASE_URL_LOCAL');
>>>>>>> 2d06b81720a8f4dda55105f12ce04cd7957ab225

        return $dataPostgre->connect();
    }
}
?>