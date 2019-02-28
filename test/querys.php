<?php

require_once '../tools/db/Querys.php';

define('SET', 'user_id, email, invite_code, registration_code, username, names, lastnames, age, image, phone, points, movile_data, create_date, update_date');

try {
   $userQuery = new Querys('test');
   $user = $userQuery->select('id', '1', 'data');

   foreach ($user as $key => $value) {
      echo $value->data . "\n\r";
   }

} catch (Exception $th) {
   echo 'ERROR = ' . $th->getMessage();
}
