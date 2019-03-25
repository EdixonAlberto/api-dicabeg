<?php

namespace V2\Database;

use V2\Database\Execute;

class Querys extends Execute
{
    public function __construct()
    {
    }

    public static function table(string $table)
    {
        $query = new Querys();
        $query->table = $table;
        return $query;
    }

    public function select(string $fields)
    {
        $this->sql = "SELECT {$fields} FROM {$this->table}";
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
        $setLenght = count($arraySet);
        $index = 1;

        foreach ($arraySet as $set => $value) {
            if ($index++ == $setLenght) {
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
        $setLenght = count($arraySet);
        $index = 1;

        foreach ($arraySet as $set => $value) {
            if ($index++ == $setLenght) {
                $setUpdate .= "{$set} = ?";
            } else {
                $setUpdate .= "{$set} = ?, ";
            }
        }

        $this->sql = "UPDATE {$this->table} SET {$setUpdate}";
        $this->arraySet = $arraySet;
        return $this;
    }

    public function delete()
    {
        $this->sql = "DELETE FROM {$this->table}";
        return $this;
    }

    public function where(string $column, string $value)
    {
        $this->sql .= " WHERE {$column} = ?";
        $this->value = $value;
        return $this;
    }
}
