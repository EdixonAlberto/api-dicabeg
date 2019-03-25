<?php

namespace V2\Modules;

use V2\Database\Querys;
use V2\Modules\JsonResponse;

class Time
{
   public static function current($format = null)
   {
      date_default_timezone_set('America/Caracas');
      $current = $_SERVER['REQUEST_TIME'];

      $arrayCurrentTime = [
         'UNIX' => $current,
         'UTC' => date('Y-m-d H:i:s', $current)
      ];
      return $arrayCurrentTime[$format] ??
         $arrayCurrentTime;
   }

   public static function expiration($format = null)
   {
      $optionQuery = new Querys('options');

      $option = $optionQuery->selectAll('expiration_time')[0];
      $expiration = strtotime(self::current('UTC') . "+ {$option->expiration_time}");

      $arrayExpirationTime = [
         'UNIX' => $expiration,
         'UTC' => date('Y-m-d H:i:s', $expiration)
      ];
      return $arrayExpirationTime[$format] ?? $arrayExpirationTime;
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
