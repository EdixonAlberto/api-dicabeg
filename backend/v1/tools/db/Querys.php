<?php

require_once 'PgSqlConnection.php';

class Querys
{
   private $table;
   private $setSelect;
   private $setInsert;

   public function __construct($table)
   {
      $this->table = $table;
      switch ($table) {

         case 'users':
            $this->setSelect = 'user_id, email, invite_code, username, names, lastnames, age, image, phone, points, movile_data, create_date, update_date';
            $this->setInsert = 'user_id, email, password, invite_code, registration_code, username, create_date';
            $this->setValues = '?, ?, ?, ?, ?, ?, ?';
            break;

         case 'sessions':
            $this->set = '';
            break;

         case 'referrals':
            $this->set = 'referred_id';
            break;

         case 'history':
            $this->set = '';
            break;
      }
   }

   public function select($column, $value, $count = false)
   {
      $set = ($count) ? 'COUNT(user_id)' : $this->set;
      $sql = "SELECT {$set}
               FROM {$this->table}
               WHERE {$column} = ?";

      $query = PgSqlConnection::connection()->prepare($sql);
      $query->execute([
         $value
      ]);
      $rows = $query->rowCount();
      if ($rows > 1) {
         for ($i = 0; $i < $rows; $i++) {
            $objIndexedByColumns = $query->fetch(PDO::FETCH_OBJ);
            $response[] = $objIndexedByColumns;
         }
      } else $response = $query->fetch(PDO::FETCH_OBJ);
      return ($search) ? $response->count : $response;
   }

   public function insert($arraySet)
   {
      $sql = "INSERT INTO {$this->table} ({$this->setInsert})
               VALUES ($this->setValues)";

      $query = PgSqlConnection::connection()->prepare($sql);
      $setLenght = count($arraySet);
      for ($i = 0; $i < $setLenght; $i++) {
         $query->bindValue($i + 1, $arraySet[$i]);
      }
      $query->bindValue($setLenght + 1, date('Y-m-d H:i'));
      $query->execute();
      return $query->errorInfo();
   }
}
