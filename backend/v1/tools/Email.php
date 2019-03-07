<?php

namespace Tools;

use Lib\SendGrid;

class Email extends SendGrid
{
   static function send()
   {
      self::generateEmail(
         'suport@dicabeg.com',
         'Hello! from api',
         'edixonalbertto@gmail.com',
         '<h1>testing</h1>'
      );
   }
}
