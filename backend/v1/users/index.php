<?php

require_once __DIR__ . '../../../../vendor/autoload.php';

use Tools\JsonResponse;
use Tools\Validations;
use V1\Sessions\Sessions;
use V1\Users\Users;

$method = $_SERVER['REQUEST_METHOD'];
parse_str(file_get_contents('php://input'), $_REQUEST);

try {
    if ($method != 'POST' and $_GET['id'] != 'alls') {
        Validations::gui();
        Sessions::verifySession();
    }

    switch ($method) {
        case 'GET':
            ($_GET['id'] == 'alls') ?
                Users::index() :
                Users::show();
            break;

        case 'POST':
            Users::store();
            break;

        case 'PATCH':
            Users::update();
            break;

        case 'DELETE':
            Users::destroy();
            break;
    }
} catch (Exception $error) {
    $response = $error->getMessage();
    $code = $error->getCode();
    JsonResponse::error($response, $code);
}
