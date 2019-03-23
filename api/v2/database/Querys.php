<?php

namespace V2\Database;

use V2\Database\PgSqlConnection;

class Querys extends PgSqlConnection
{
   private $table;

   public function __construct($table = null)
   {
      $this->table = $table;
   }

   public static function table(string $table)
   {
      $query = new Querys();
      $query->table = $table;
      return $query;
   }

   public function select(str $fields) //TODO: diferencia de string a str
   {
      $sql = "SELECT {$fields} FROM {$this->table}";
      $this->sql = $sql;
      return $this;
   }

   public function update($arraySet)
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

   public static function delete($table)
   {
      $sql = "DELETE FROM {$table}";
      $query = new Querys();
      $query->sql = $sql;
      return $query;
   }

   function where($column, $value)
   {
      $sql = $this->sql . " WHERE {$column} = ?";
      $this->sql = $sql;
      $this->value = $value;
      return $this;
   }

   function get(int $row = 0)
   {
      $rows = self::execute($query);
      if ($rows == false) return false;

      if ($row > 0) {
         if ($rows != $row - 1) {
            $i = 0;
            while ($i++ < $row) {
               $objIndexedByColumns = $query->fetch(\PDO::FETCH_OBJ);
               $arrayResponse[] = $objIndexedByColumns;
            }
            return $arrayResponse;
         } else return false;
      } else return $query->fetch(\PDO::FETCH_OBJ);
   }

   function getAll()
   {
      $rows = self::execute($query);
      if ($rows == false) return false;

      for ($i = 0; $i < $rows; $i++) {
         $objIndexedByColumns = $query->fetch(\PDO::FETCH_OBJ);
         $arrayResponse[] = $objIndexedByColumns;
      }
      return $arrayResponse;
   }

   function execute(&$query = null)
   {
      $query = self::connection()->prepare($this->sql);

      if (isset($this->value)) {
         $query->execute([
            $this->value
         ]);
      } else $query->execute();

      $error = $query->errorInfo()[2];
      if (is_null($error)) return $query->rowCount();
      else throw new \Exception($error, 400);
   }



/*
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
    */


}
