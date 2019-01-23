<?php

// $method = $_SERVER['REQUEST_METHOD'];

include 'security.php';
use referrals\security;

echo security::validateEmail('>edi@gmail.com'); die;


switch ($method) {
    case 'GET':
        var_dump($_GET); die;
    break;

    case 'PUT':
        parse_str(file_get_contents('php://input'), $_PUT);

        var_dump($_PUT);
        foreach ($_PUT as $key => $value) {
            var_dump($key);
        $ar = json_decode($key, true);
            var_dump($ar);
        }
    break;
}

?>