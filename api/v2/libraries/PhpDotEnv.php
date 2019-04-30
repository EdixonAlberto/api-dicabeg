<?php

namespace V2\Libraries;

class PhpDotEnv
{
   public static function __callStatic($name, $arguments)
   {
      if (getenv('DATABASE_URL') == false) {
         $environment = \Dotenv\Dotenv::create(__DIR__ . '../../../../');
         $environment->load();
      }

      define('DATABASE_URL', getenv('DATABASE_URL'));
      define('SECRET_KEY', getenv('SECRET_KEY'));
      define('SENDGRID_API_KEY', getenv('SENDGRID_API_KEY'));
      define('ONESIGNAL_API_ID', getenv('ONESIGNAL_API_ID'));
      define('ONESIGNAL_API_KEY', getenv('ONESIGNAL_API_KEY'));
   }
}
