<?php

namespace V2\Modules;

use Exception;
use V2\Database\Querys;

class Time
{
   public static function current(string $tz = null) : object
   {
      self::setTimeZone($tz);
      $current = $_SERVER['REQUEST_TIME'];

      $currentTime = (object)[
         'unix' => $current,
         'utc' => date('Y-m-d H:i:s', $current)
      ];
      return $currentTime;
   }

   public static function expiration()
   {
      $expiration_time = Querys::table('options')
         ->select('expiration_time')
         ->get(function () {
            $error = 'expiration time not configured in the database';
            throw new Exception($error, 500);
         });

      $expiration = strtotime(self::current()->utc . '+' . $expiration_time);

      $expirationTime = (object)[
         'unix' => $expiration,
         'utc' => date('Y-m-d H:i:s', $expiration)
      ];
      return $expirationTime;
   }

   public static function setTimeZone(string $tz = null) : void
   {
      global $timeZone;

      if (!is_null($tz)) {
         $timeZone = $tz;
         date_default_timezone_set($timeZone);

      } elseif (!isset($timeZone)) {
         $timeZone = Querys::table('accounts')
            ->select('time_zone')
            ->where('user_id', USERS_ID)->get();

         date_default_timezone_set($timeZone);
      }
   }

   // public static function getExpiration()
   // {
   //    $optionQuery = new Querys('options');

   //    $option = $optionQuery->selectAll('expiration_time')[0];
   //    $expiration = $option->expiration_time;

   //    JsonResponse::read('expiration_time', $expiration);
   // }

   // public static function setExpiration()
   // {
   //    $optionQuery = new Querys('options');

   //    $arrayOption['expiration_time'] = $_REQUEST['time'] . ' minute';
   //    $optionQuery->update(false, false, $arrayOption);

   //    JsonResponse::updated('options', $arrayOption);
   // }
}
