<?php

require_once __DIR__ . '../../../../vendor/autoload.php';
\Lib\PhpDotEnv::load();

use Tools\Validations;
use Tools\JsonResponse;
use V1\Sessions\Sessions;

$method = $_SERVER['REQUEST_METHOD'];
parse_str(file_get_contents('php://input'), $_REQUEST);

try {
    if ($method != 'GET' and $method != 'POST') Validations::token();

    switch ($method) {
        case 'GET':
            Sessions::index();
            break;

        case 'POST':
            Sessions::store();
            break;

        case 'PATCH':
            Sessions::update();
            break;

        case 'DELETE':
            Sessions::destroy();
            break;
    }
} catch (Exception $error) {
    $response = $error->getMessage();
    $code = $error->getCode();
    JsonResponse::error($response, $code);
}
