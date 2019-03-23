<?php

namespace V2\Library;

use Dotenv\Dotenv;

class PhpDotEnv
{
   public static function load()
   {
      if (getenv('DATABASE_URL') == false) {
         $path = __DIR__ . '../../../../';
         $env = Dotenv::create($path);
         $env->load();
      }
   }
}
