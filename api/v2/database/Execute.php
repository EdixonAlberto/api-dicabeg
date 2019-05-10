<?php

namespace V2\Database;

use PDO;
use Exception;
use V2\Database\PgSqlConnection;

class Execute
{
    // TODO: devolver el numero de resultados obtenidos o restantes
    public function get($callback = false)
    {
        $result = self::execute($callback);

        if ($result) {
            if (is_array($this->fields))
                $queryResult = $this->query->fetch(PDO::FETCH_OBJ);

            else {
                $property = $this->fields;
                $queryResult = $this->query
                    ->fetch(PDO::FETCH_OBJ)->$property;
            }
        } else return false;

        return $queryResult;
    }

    public function getAll($callback = false)
    {
        $result = self::execute($callback);

        if ($result) {
            for ($i = 0; $i < $result; $i++)
                $arrayQuery[] = $this->query->fetch(PDO::FETCH_OBJ);
            return $arrayQuery;

        } else return false;
    }

    // // ADD: puede ser util un metodo para buscar resultados, por ahora se realiza con get
    // public function field($found, $function)
    // {
    //     $rows = self::execute($query);

    //     $rows = ($rows < 1) ? false : true;
    //     if ($rows == $found) $function();
    // }

    public function execute($callback = false)
    {
        $query = PgSqlConnection::connection()->prepare($this->sql);
        $queryType = substr($this->sql, 0, 6);

        $index = 1;
        if ($queryType == 'INSERT' or $queryType == 'UPDATE') {
            foreach ($this->arraySet as $value) $query->bindValue($index++, $value);

            if (isset($this->value)) {
                if (is_array($this->value)) {
                    foreach ($this->value as $value) $query->bindValue($index++, $value);

                } else $query->bindValue($index, $this->value);
            }

        } elseif ($queryType == 'SELECT' or $queryType == 'DELETE') {
            if (isset($this->value)) {
                if (is_array($this->value)) {
                    foreach ($this->value as $value) $query->bindValue($index++, $value);

                } else $query->execute([$this->value]);
            }
        }
        $query->execute();

        $error = $query->errorInfo()[2];

        if (is_null($error)) {
            unset($this->value);

            $rows = $query->rowCount();
            if ($rows > 0) {
                $this->query = $query;
                return $rows;

            } else {
                if ($callback) $callback();
                else return false;
            }

        } else throw new Exception($error, 500);
    }
}
