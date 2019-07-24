<?php

namespace V2\Modules;

use Exception;
use V2\Database\Querys;
use V2\Middleware\Auth;

class Time
{
   public static $timeZone;

   public static function current(): object
   {
      self::setTimeZone();
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

   public static function setTimeZone(): void
   {
      if (isset(self::$timeZone)) {
         date_default_timezone_set(self::$timeZone);
      } else {
         self::$timeZone = Querys::table('accounts')
            ->select('time_zone')
            ->where('user_id', Auth::$id)
            ->get();

         date_default_timezone_set(self::$timeZone);
      }
   }
}
