<?php

require_once 'DataBase.php';

class PgSqlConnection
{
    public static function connection()
    {
        try {
            $dataPostgre = new DataBase('pgsql', 'DATABASE_URL');
            return $dataPostgre->connect();
        } catch (Exception $th) {
            echo $th;
        }
    }
}
