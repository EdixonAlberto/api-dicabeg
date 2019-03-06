<?php

require_once __DIR__ . '../../../vendor/autoload.php';

use Tools\Gui;

var_dump(
   Gui::generate('code')
);

/*
2C76E7E5-ED6E-4A70-BB79-74B771CF4641
CD19B2D4-C736-4D4C-8268-E6AA68374A2E
F35D80700BBE42CE8A69F18673AA6316

$pass = 'admin';
$salt = mcrypt_create_iv(22, $pass);
$salt = base64_encode($salt);
$salt = str_replace('+', '.', $salt);
$hash = crypt('rasmuslerdorf', '$2y$10$' . $salt . '$');
echo $hash;
 */