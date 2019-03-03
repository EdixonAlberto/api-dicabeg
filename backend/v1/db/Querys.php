<?php

namespace Db;

use Db\PgSqlConnection;
use Exception;
use PDO;

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
            $objIndexedByColumns = $query->fetch(PDO::FETCH_OBJ);
            $arrayResponse[] = $objIndexedByColumns;
         }
         return $arrayResponse;
      } else return false;
   }

   public function select($column, $condition, $fields = null, $all = false)
   {
      $fields = $fields ?? $column;

      $sql = "SELECT {$fields} FROM {$this->table}
               WHERE {$column} = ?";

      $query = self::connection()->prepare($sql);;
      $query->execute([
         $condition
      ]);

      $rows = $query->rowCount();
      if ($rows > 1 or $all == true) { // return array
         for ($i = 0; $i < $rows; $i++) {
            $objIndexedByColumns = $query->fetch(PDO::FETCH_OBJ);
            $arrayResponse[] = $objIndexedByColumns;
         }
         return $arrayResponse;
      } elseif ($rows == 1) return $query->fetch(PDO::FETCH_OBJ);
      else return false;
   }

   public function insert($arraySet)
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
            $setValues .= '?, '; // Para construir la expresion: VALUES (?, ?, ...)";
         }
      }
      $index = 1;

      $sql = "INSERT INTO {$this->table} ({$setInsert})
               VALUES ({$setValues})";

      $query = self::connection()->prepare($sql);
      foreach ($arraySet as $value) $query->bindValue($index++, $value);
      $query->execute();

      $error = $query->errorInfo()[2];
      if (is_null($error)) return true;
      else throw new Exception($error, 400);
   }

   public function update($column, $condition, $arraySet)
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
      $index = 1;

      $sql = "UPDATE {$this->table} SET {$setUpdate}
               WHERE {$column} = ?";

      $query = self::connection()->prepare($sql);
      foreach ($arraySet as $value) $query->bindValue($index++, $value);
      $query->bindValue($index, $condition);
      $query->execute();

      $error = $query->errorInfo()[2];
      if (is_null($error)) return true;
      else throw new Exception($error, 400);
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
