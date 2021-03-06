<?php

namespace V2\Database;

class Querys extends Execute
{
    private const REGISTERS_NRO = 10;

    public static function table(string $table): Querys
    {
        $query = new Querys;
        $query->table = $table;
        return $query;
    }

    public function select($fields): Querys
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

    public function group(int $number, string $order = null): Querys
    {
        $start = self::REGISTERS_NRO * ($number - 1);
        $_order = $order ?? 'desc';
        $this->sql .= " ORDER BY create_date {$_order} OFFSET {$start} LIMIT " . self::REGISTERS_NRO;
        return $this;
    }

    public function insert($Sets): Querys
    {
        $setInsert = $setValues = '';
        foreach ($Sets as $set => $value) {
            $setInsert .= "{$set}, ";
            $setValues .= '?, ';
        }
        $setInsert = substr($setInsert, 0, strrpos($setInsert, ','));
        $setValues = substr($setValues, 0, strrpos($setValues, ','));

        $this->sql = "INSERT INTO {$this->table} ({$setInsert})
                        VALUES ({$setValues})";
        $this->Sets = $Sets;
        return $this;
    }

    public function update($Sets)
    {
        $setUpdate = '';
        foreach ($Sets as $set => $value) {
            if (!is_null($value)) {
                $setUpdate .= "{$set} = ?, ";
            } else {
                if (is_array($Sets)) unset($Sets[$set]);
                else unset($Sets->$set);
            }
        }

        $setUpdate = substr($setUpdate, 0, strrpos($setUpdate, ','));

        $this->sql = "UPDATE {$this->table} SET {$setUpdate}";
        $this->Sets = $Sets;
        return $this;
    }

    public function delete()
    {
        $this->sql = "DELETE FROM {$this->table}";
        return $this;
    }

    public function where($column, string $value = null): Querys
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
