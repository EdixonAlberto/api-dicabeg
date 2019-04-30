<?php

namespace V2\Database;

class Execute
{
    // TODO: devolver el numero de resultados obtenidos o restantes
    public function get($callback = false)
    {
        $rows = self::execute($query);
        $rows = ($rows < 1) ? false : true;

        $queryResult = false;
        if ($rows) {
            if (is_array($this->fields))
                $queryResult = $query->fetch(\PDO::FETCH_OBJ);

            else {
                $property = $this->fields;
                $queryResult = $query->fetch(\PDO::FETCH_OBJ)->$property;
            }
        } elseif ($callback) $callback();

        return $queryResult;
    }

    public function getAll(bool $resul = null, $exception = null)
    {
        $rows = self::execute($query);
        $rows = ($rows < 1) ? false : true;

        if ($rows === $resul) $exception();

        if ($rows) {
            for ($i = 0; $i < $rows; $i++)
                $arrayResponse[] = $query->fetch(\PDO::FETCH_OBJ);
            return $arrayResponse;
        }
    }

    // // ADD: puede ser util un metodo para buscar resultados, por ahora se realiza con get
    // public function field($found, $function)
    // {
    //     $rows = self::execute($query);

    //     $rows = ($rows < 1) ? false : true;
    //     if ($rows == $found) $function();
    // }

    public function execute(string &$query = null)
    {
        $query = \V2\Database\PgSqlConnection::connection()->prepare($this->sql);
        $queryType = substr($this->sql, 0, 6);

        // DEBUG:
        // var_dump($this->sql, $this->arraySet);

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
        if (is_null($error)) {
            unset($this->value);
            return $query->rowCount();
        } else throw new \Exception($error, 400);
    }
}
