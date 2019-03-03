<?php

namespace Db;

use Db\DataBase;

class PgSqlConnection extends DataBase
{
    protected static function connection()
    {
        $dataPostgre = new DataBase('pgsql', 'DATABASE_URL');
        return $dataPostgre->conn();
    }
}
