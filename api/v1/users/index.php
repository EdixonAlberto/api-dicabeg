<?php

require_once __DIR__ . '../../../../vendor/autoload.php';
\Lib\PhpDotEnv::load();

use Tools\Security;
use V1\Users\Users;
use Tools\Validations;
use Tools\JsonResponse;

$method = $_SERVER['REQUEST_METHOD'];
parse_str(file_get_contents('php://input'), $_REQUEST);
$_GET ?? Validations::gui();

try {
    if ($method != 'POST' and $_GET['id'] != 'alls') {
        Security::verifySession();
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
