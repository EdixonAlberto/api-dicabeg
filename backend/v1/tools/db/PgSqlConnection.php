<?php

require_once 'DataBase.php';

class PgSqlConnection
{
    public static function connection()
    {
        $dataPostgre = new dataBase('pgsql', 'DATABASE_URL');
        return $dataPostgre->connect();
    }
}
