<?php

require_once __DIR__ . '../../../../vendor/autoload.php';

use Tools\JsonResponse;
use V1\Options\Options;

$method = $_SERVER['REQUEST_METHOD'];
parse_str(file_get_contents('php://input'), $_REQUEST);

if (isset($_GET['id'])) {
   if ($_GET['id'] === 'time') {
      if ($method == 'GET') {
         $time = Options::expirationTime();
         $time = trim($time, '+');

         echo json_encode(['expirationTime' => $time]);

      } elseif ($method == 'POST') {
         $time = Options::expirationTime();
         $time = trim($time, '+');

         Options::setExpirationTime($time, $_REQUEST['time'] . ' minute');
      }
   } else JsonResponse::error('not found resource', 400);
}
