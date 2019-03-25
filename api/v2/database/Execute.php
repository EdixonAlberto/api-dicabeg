<?php

namespace V2\Database;

use V2\Database\PgSqlConnection;

class Execute extends PgSqlConnection
{
    function get(int $cantidad = 0)
    {
        $rows = self::execute($query);
        if ($rows == false) return false;

        if ($cantidad > 0) {
            $i = 0;
            while ($i < $rows and $i++ < $cantidad) {
                $arrayResponse[] = $query->fetch(\PDO::FETCH_OBJ);
            }
            return $arrayResponse;
        } else return $query->fetch(\PDO::FETCH_OBJ);
    }

    function getAll()
    {
        $rows = self::execute($query);
        if ($rows == false) return false;

        $i = 0;
        while ($i++ < $rows) {
            $arrayResponse[] = $query->fetch(\PDO::FETCH_OBJ);
        }
        return $arrayResponse;
    }

    public function execute(string &$query = null)
    {
        $query = self::connection()->prepare($this->sql);
        $queryType = substr($this->sql, 0, 6);

        // var_dump($this->sql, $queryType); // DEBUG:

        if ($queryType == 'INSERT' or $queryType == 'UPDATE') {
            $index = 1;
            foreach ($this->arraySet as $value) $query->bindValue($index++, $value);
            if (isset($this->value)) $query->bindValue($index, $this->value);
            $query->execute();

        } elseif ($queryType == 'SELECT' or $queryType == 'DELETE') {
            if (isset($this->value)) {
                $query->execute([
                    $this->value
                ]);
            } else $query->execute();
        }

        $error = $query->errorInfo()[2];
        if (is_null($error)) return $query->rowCount();
        else throw new \Exception($error, 400);
    }
}
