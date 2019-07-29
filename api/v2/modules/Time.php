<?php

namespace V2\Modules;

use Exception;
use V2\Modules\User;
use V2\Database\Querys;

class Time
{
   public static $timeZone;

   public static function current(string $timeZone = null): object
   {
      self::setTimeZone($timeZone);
      $current = $_SERVER['REQUEST_TIME'];

      $currentTime = (object) [
         'unix' => $current,
         'utc' => date('Y-m-d H:i:s', $current)
      ];
      return $currentTime;
   }

   public static function expiration(): object
   {
      $expiration_time = Querys::table('options')
         ->select('expiration_time')
         ->get(function () {
            throw new Exception(
               'expiration time not configured in the database',
               500
            );
         });

      $expiration = strtotime(self::current()->utc . '+' . $expiration_time);

      $expirationTime = (object) [
         'unix' => $expiration,
         'utc' => date('Y-m-d H:i:s', $expiration)
      ];
      return $expirationTime;
   }

   private static function setTimeZone(string $timeZone = null): void
   {
      if (is_null($timeZone)) {
         if (is_null(self::$timeZone)) {
            self::$timeZone = Querys::table('accounts')
               ->select('time_zone')
               ->where('email', User::$email)
               ->get();
         }
      } else self::$timeZone = $timeZone;
      date_default_timezone_set(self::$timeZone);
   }
}
