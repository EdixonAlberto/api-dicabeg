<?php

namespace V1\Options;

use PDO;
use Exception;
use Db\PgSqlConnection;

class Options
{
   public static function expirationTime()
   {
      $sql = "SELECT expiration_time
              FROM options";
      $query = PgSqlConnection::connection()->prepare($sql);
      $query->execute();

      $time = $query->fetch(PDO::FETCH_OBJ);
      return '+' . $time->expiration_time;
   }

   public static function setExpirationTime($condition, $time)
   {
      $sql = "UPDATE options
               SET expiration_time = ?
               WHERE expiration_time = ?";

      $query = PgSqlConnection::connection()->prepare($sql);
      $query->execute([
         $time,
         $condition
      ]);

      $error = $query->errorInfo()[2];
      if (is_null($error)) {
         echo json_encode(['expiration_time' => $time]);
      } else throw new Exception($error, 400);
   }
}
