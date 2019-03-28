<?php

namespace Lib;

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
