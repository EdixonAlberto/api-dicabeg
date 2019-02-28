<?php

require_once __DIR__ . '../../../../vendor/autoload.php';

use Tools\JsonResponse;
use Tools\Validations;
use V1\Sessions\Sessions;

$method = $_SERVER['REQUEST_METHOD'];
parse_str(file_get_contents('php://input'), $_REQUEST);

try {
    if ($method != 'POST') {
        Validations::id();
        Sessions::verifySession();
    }

    switch ($method) {
        case 'GET':
            ($_GET['id'] == 'alls') ?
                Sessions::getSessionsAlls() :
                Sessions::getSessionsById();
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
