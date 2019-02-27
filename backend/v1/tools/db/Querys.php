<?php

require_once 'PgSqlConnection.php';

class Querys extends PgSqlConnection
{
   private $table;

   public function __construct($table)
   {
      $this->table = $table;
   }

   // public function search($column, $condition)
   // {
   //    $sql = "SELECT {$column} FROM {$this->table}
   //             WHERE {$column} = ? LIMIT 1";

   //    $query = self::connection()->prepare($sql);
   //    $query->execute([
   //       $condition
   //    ]);

   //    $rows = $query->rowCount();
   //    if ($rows) return true;
   //    else throw new Exception('not found resourse', 404);
   // }

   public function select($column, $condition, $fields)
   {
      $sql = "SELECT {$fields} FROM {$this->table}
               WHERE {$column} = ?";

      $query = self::connection()->prepare($sql);
      $query->execute([
         $condition
      ]);

      $rows = $query->rowCount();
      if ($rows) {
         for ($i = 0; $i < $rows; $i++) {
            $objIndexedByColumns = $query->fetch(PDO::FETCH_OBJ);
            $arrayResponse[] = $objIndexedByColumns;
         }
         return $arrayResponse;
      } else return false;
   }

   public function insert($arraySet)
   {
      $setInsert = $setValues = '';
      $index = 1;
      foreach ($arraySet as $key => $value) {
         $setInsert .= "{$key}, ";
         $setValues .= '?, '; // Para construir la expresion: VALUES (?, ?, ...)";
      }

      $sql = "INSERT INTO {$this->table} ({$setInsert}create_date)
               VALUES ({$setValues}?)";

      $query = self::connection()->prepare($sql);
      foreach ($arraySet as $value) $query->bindValue($index++, $value);
      $query->bindValue($index, date('Y-m-d H:i'));
      $query->execute();

      $error = $query->errorInfo()[2];
      if (is_null($error)) return true;
      else throw new Exception($error, 400);
   }

   public function update($column, $condition, $arraySet)
   {
      $setUpdate = '';
      $index = 1;
      foreach ($arraySet as $key => $value) {
         $setUpdate .= "{$key} = ?, ";
      }

      $sql = "UPDATE {$this->table} SET {$setUpdate}update_date = ?
               WHERE {$column} = ?";

      $query = self::connection()->prepare($sql);
      foreach ($arraySet as $value) $query->bindValue($index++, $value);
      $query->bindValue($index++, date('Y-m-d H:i'));
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
      if ($rows) return true;
      else throw new Exception('delete failed', 500);
   }
}
