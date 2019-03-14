<?php

require_once __DIR__ . '../../../../vendor/autoload.php';

use Tools\JsonResponse;
use V1\Options\Time;

$method = $_SERVER['REQUEST_METHOD'];
parse_str(file_get_contents('php://input'), $_REQUEST);

if (isset($_GET['id'])) {
   if ($_GET['id'] != 'time') JsonResponse::error('not found resource', 400);

   if ($method == 'GET') Time::getExpiration();
   elseif ($method == 'PATCH') Time::setExpiration();
}
