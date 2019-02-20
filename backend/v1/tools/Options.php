<?php

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
}
