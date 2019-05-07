<?php

namespace V2\Modules;

use V2\Database\Querys;

class Time
{
   public static function current()
   {
      date_default_timezone_set('America/Caracas'); // ADD: Esta info debe ser proporcionada por el front
      $current = $_SERVER['REQUEST_TIME'];

      $arrayCurrentTime = (object)[
         'unix' => $current,
         'utc' => date('Y-m-d H:i:s', $current)
      ];
      return $arrayCurrentTime;
   }

   public static function expiration()
   {
      $expiration_time = Querys::table('options')->select('expiration_time')->get();
      $expiration = strtotime(self::current()->utc . '+' . $expiration_time);

      $arrayExpirationTime = (object)[
         'unix' => $expiration,
         'utc' => date('Y-m-d H:i:s', $expiration)
      ];
      return $arrayExpirationTime;
   }

   public static function getExpiration()
   {
      $optionQuery = new Querys('options');

      $option = $optionQuery->selectAll('expiration_time')[0];
      $expiration = $option->expiration_time;
      JsonResponse::read('expiration_time', $expiration);
   }

   public static function setExpiration()
   {
      $optionQuery = new Querys('options');

      $arrayOption['expiration_time'] = $_REQUEST['time'] . ' minute';
      $optionQuery->update(false, false, $arrayOption);
      JsonResponse::updated('options', $arrayOption);
   }
}
