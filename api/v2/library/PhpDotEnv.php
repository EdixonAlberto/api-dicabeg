<?php

namespace V2\Library;

use Dotenv\Dotenv;

class PhpDotEnv
{
   public static function load()
   {
      if (getenv('DATABASE_URL') == false) {
         var_dump('aqui');
         $env = Dotenv::create(__DIR__ . '../../../../');
         $env->load();
      }
   }
}
