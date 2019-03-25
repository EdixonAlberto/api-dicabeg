<?php

namespace V2\Database;

use V2\Database\QuerysExecute;

class Querys extends QuerysExecute
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

   public function insert(array $arraySet)
   {
      $setInsert = $setValues = '';
      $setLenght = count($arraySet);
      $index = 1;

      foreach ($arraySet as $set => $value) {
         if ($index++ == $setLenght) {
            $setInsert .= $set;
            $setValues .= "'{$value}'";
         } else {
            $setInsert .= "{$set}, ";
            $setValues .= "'{$value}', ";
         }
      }

      $this->sql = "INSERT INTO {$this->table} ({$setInsert})
                     VALUES ({$setValues})";
      return $this;
   }

   public function update(array $arraySet)
   {
      $setUpdate = '';
      $setLenght = count($arraySet);
      $index = 1;

      foreach ($arraySet as $set => $value) {
         if ($index++ == $setLenght) {
            $setUpdate .= "{$set} = '{$value}'";
         } else {
            $setUpdate .= "{$set} = '{$value}', ";
         }
      }

      $this->sql = "UPDATE {$this->table} SET {$setUpdate}";
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
