<?php

namespace V2\Database;

use V2\Database\DB;

class PgSqlConnection extends DB
{
    protected static function connection()
    {
        $dataPostgre = new DB('pgsql', 'DATABASE_URL');
        return $dataPostgre->connect();
    }
}
