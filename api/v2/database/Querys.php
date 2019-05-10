<?php

namespace V2\Database;

class Querys extends Execute
{
    public static function table(string $table) : Querys
    {
        $query = new Querys;
        $query->table = $table;
        return $query;
    }

    public function select($fields) : Querys
    {
        $_fields = '';
        if (is_array($fields)) {
            foreach ($fields as $field) {
                $_fields .= "{$field}, ";
            }
            $_fields = substr($_fields, 0, strrpos($_fields, ','));

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

    // TODO: modificar este metodo
    public function insert(array $arraySet) : Querys
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

        $setUpdate = substr($setUpdate, 0, strrpos($setUpdate, ','));

        $this->sql = "UPDATE {$this->table} SET {$setUpdate}";
        $this->arraySet = $arraySet;
        return $this;
    }

    public function delete()
    {
        $this->sql = "DELETE FROM {$this->table}";
        return $this;
    }

    public function where($column, string $value = null) : Querys
    {
        $_column = '';
        if (is_array($column)) {
            foreach ($column as $key => $value) {
                $_column .= "{$key} = ? and ";
                $_value[] = $value;
            }

            $_column = substr($_column, 0, strrpos($_column, 'and') - 1);
            $this->sql .= " WHERE {$_column}";
            $this->value = $_value;

        } else {
            $_column = $column;
            $this->sql .= " WHERE {$_column} = ?";
            $this->value = $value;
        }

        return $this;
    }
}
