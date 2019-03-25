<?php

namespace V2\Database;

use V2\Database\PgSqlConnection;

class QuerysExecute extends PgSqlConnection
{
    function get(int $row = 0)
    {
        $rows = self::execute($query);
        if ($rows == false) return false;

        if ($row > 0) {
            if ($rows != $row - 1) {
                $i = 0;
                while ($i++ < $row) {
                    $objIndexedByColumns = $query->fetch(\PDO::FETCH_OBJ);
                    $arrayResponse[] = $objIndexedByColumns;
                }
                return $arrayResponse;
            } else return false;
        } else return $query->fetch(\PDO::FETCH_OBJ);
    }

    function getAll()
    {
        $rows = self::execute($query);
        if ($rows == false) return false;

        for ($i = 0; $i < $rows; $i++) {
            $objIndexedByColumns = $query->fetch(\PDO::FETCH_OBJ);
            $arrayResponse[] = $objIndexedByColumns;
        }
        return $arrayResponse;
    }

    public function execute(&$query = null)
    {
        $query = self::connection()->prepare($this->sql);
        var_dump($this->sql); // DEBUG:

        if (isset($this->value)) {
            $query->execute([$this->value]);
        } else $query->execute();

        $error = $query->errorInfo()[2];
        if (is_null($error)) return $query->rowCount();
        else throw new \Exception($error, 400);
    }

/* TODO
        $rows = $query->rowCount();
        if ($rows == false) return false;

        for ($i = 0; $i < $rows; $i++) {
            $objIndexedByColumns = $query->fetch(\PDO::FETCH_OBJ);
            $arrayResponse[] = $objIndexedByColumns;
        }
        return $arrayResponse;
     */
}
