<?php

namespace V2\Database;

class Querys extends Execute
{
    public static function table(string $table)
    {
        $query = new Querys;
        $query->table = $table;
        return $query;
    }

    public function select($fields)
    {
        $_fields = '';
        if (is_array($fields)) {
            foreach ($fields as $field) {
                $_fields .= "{$field}, ";
            }
            $_fields = self::cleanCadena($_fields);

        } else $_fields = $fields;

        $this->sql = "SELECT {$_fields} FROM {$this->table}";
        $this->fields = $fields;
        return $this;
    }

    public function group(int $number)
    {
        $start = 10 * ($number - 1);
        $this->sql .= " OFFSET {$start} LIMIT 10";
        return $this;
    }

    public function insert(array $arraySet)
    {
        $setInsert = $setValues = '';
        $parametersNumber = count($arraySet);
        $index = 1;

        foreach ($arraySet as $set => $value) {
            if ($index++ == $parametersNumber) {
                $setInsert .= $set;
                $setValues .= '?';
            } else {
                $setInsert .= "{$set}, ";
                $setValues .= '?, ';
            }
        }

        $this->sql = "INSERT INTO {$this->table} ({$setInsert})
                        VALUES ({$setValues})";
        $this->arraySet = $arraySet;
        return $this;
    }

    public function update(array $arraySet)
    {
        $setUpdate = '';
        foreach ($arraySet as $set => $value) {
            if (!is_null($value)) {
                $setUpdate .= "{$set} = ?, ";
            } else unset($arraySet[$set]);
        }

        $setUpdate = self::cleanCadena($setUpdate);

        $this->sql = "UPDATE {$this->table} SET {$setUpdate}";
        $this->arraySet = $arraySet;
        return $this;
    }

    public function delete()
    {
        $this->sql = "DELETE FROM {$this->table}";
        return $this;
    }

    // ADD: aceptar parametros array para concatenar varias condiciones con AND
    public function where(string $column, string $value)
    {
        $this->sql .= " WHERE {$column} = ?";
        $this->value = $value;
        return $this;
    }

    private static function cleanCadena($sets)
    {
        return substr($sets, 0, strrpos($sets, ','));
    }
}
