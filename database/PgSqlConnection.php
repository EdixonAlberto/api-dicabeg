<?php

namespace V2\Database;

class PgSqlConnection extends DB
{
    public static function connection()
    {
        $dataPostgre = new DB('pgsql');
        return $dataPostgre->connect();
    }
}
