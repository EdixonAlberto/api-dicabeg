<?php

namespace V2\Libraries;

use Dotenv\Dotenv;
// use V2\Modules\Time;

class PhpDotEnv
{
   public function __construct()
   {
      if (getenv('DATABASE_URL') == false) {
         $environment = Dotenv::create(__DIR__ . '../../../../');
         $environment->load();
      }

      define('DATABASE_URL', getenv('DATABASE_URL'));
      define('ACCESS_KEY', getenv('ACCESS_KEY'));
      define('REFRESH_KEY', getenv('REFRESH_KEY'));
      //ADD: define('USER_TIME_ZONE',Time::getTimeZone());

      define('SENDGRID_API_KEY', getenv('SENDGRID_API_KEY'));

      define('ONESIGNAL_USER_AUTH_KEY', getenv('ONESIGNAL_USER_AUTH_KEY'));
      define('ONESIGNAL_APP_ID', getenv('ONESIGNAL_APP_ID'));
      define('ONESIGNAL_REST_API_KEY', getenv('ONESIGNAL_REST_API_KEY'));
   }
}
