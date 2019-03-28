<?php

namespace V2\Libraries;

class PhpDotEnv
{
   public static function load()
   {
      if (getenv('DATABASE_URL') == false) {
         $environment = \Dotenv\Dotenv::create(__DIR__ . '../../../../');
         $environment->load();
      }
   }
}
