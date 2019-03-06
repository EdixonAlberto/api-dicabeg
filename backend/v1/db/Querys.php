<?php

namespace Db;

use Db\PgSqlConnection;

class Querys extends PgSqlConnection
{
   private $table;

   public function __construct($table)
   {
      $this->table = $table;
   }

   public function selectAll($fields)
   {
      $sql = "SELECT {$fields} FROM {$this->table}";

      $query = self::connection()->prepare($sql);
      $query->execute();

      $rows = $query->rowCount();
      if ($rows) {
         for ($i = 0; $i < $rows; $i++) {
            $objIndexedByColumns = $query->fetch(\PDO::FETCH_OBJ);
            $arrayResponse[] = $objIndexedByColumns;
         }
         return $arrayResponse;
      } else return false;
   }

   public function select($column, $condition, $fields = null, $getArray = false)
   {
      $fields = $fields ?? $column;

      $sql = "SELECT {$fields} FROM {$this->table}
               WHERE {$column} = ?";

      $query = self::connection()->prepare($sql);;
      $query->execute([
         $condition
      ]);

      $rows = $query->rowCount();
      if ($rows == false) return false;

      if ($getArray) { // return array
         for ($i = 0; $i < $rows; $i++) {
            $objIndexedByColumns = $query->fetch(\PDO::FETCH_OBJ);
            $arrayResponse[] = $objIndexedByColumns;
         }
         return $arrayResponse;
      } else return $query->fetch(\PDO::FETCH_OBJ);
   }

   public function insert($arraySet)
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

      $sql = "INSERT INTO {$this->table} ({$setInsert})
               VALUES ({$setValues})";

      $query = self::connection()->prepare($sql);
      $query->execute();

      $error = $query->errorInfo()[2];
      if (is_null($error)) return true;
      else throw new \Exception($error, 400);
   }

   public function update($column, $condition, $arraySet)
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

      if (!$column and !$condition) {
         $sql = "UPDATE {$this->table} SET {$setUpdate}";
         $query = self::connection()->prepare($sql);
         $query->execute();

      } else {
         $sql = "UPDATE {$this->table} SET {$setUpdate}
               WHERE {$column} = ?";
         $query = self::connection()->prepare($sql);
         $query->execute([
            $condition
         ]);
      }

      $error = $query->errorInfo()[2];
      if (is_null($error)) return true;
      else throw new \Exception($error, 400);
   }

   public function delete($column, $condition)
   {
      $sql = "DELETE FROM {$this->table}
               WHERE {$column} = ?";

      $query = self::connection()->prepare($sql);
      $query->execute([
         $condition
      ]);

      $rows = $query->rowCount();
      return $rows ? true : false;
   }
}
