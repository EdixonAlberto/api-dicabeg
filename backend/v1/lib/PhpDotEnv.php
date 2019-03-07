<?php

namespace Lib;

use Dotenv\Dotenv;

class PhpDotEnv
{
   public static function load()
   {
      $path = __DIR__ . '../../../../';
      $env = Dotenv::create($path);
      $env->load();
   }
}