<?php

require_once __DIR__ . '../../../../vendor/autoload.php';
\Lib\PhpDotEnv::load();

use V1\Options\Time;
use Tools\JsonResponse;

$method = $_SERVER['REQUEST_METHOD'];
parse_str(file_get_contents('php://input'), $_REQUEST);

if (isset($_GET['id'])) {
    if ($_GET['id'] != 'time') JsonResponse::error('not found resource', 400);

    if ($method == 'GET') Time::getExpiration();
    elseif ($method == 'PATCH') Time::setExpiration();
}
