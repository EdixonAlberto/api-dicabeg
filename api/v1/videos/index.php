<?php

require_once __DIR__ . '../../../../vendor/autoload.php';

use Tools\JsonResponse;
use Tools\Security;
use Tools\Validations;
use V1\Videos\Videos;

$method = $_SERVER['REQUEST_METHOD'];
parse_str(file_get_contents('php://input'), $_REQUEST);
$_GET ?? Validations::gui();

try {
    Security::verifySession();
    switch ($method) {
        case 'GET':
            ($_GET['id'] == 'alls') ?
                Videos::index() :
                Videos::show();
            break;
    }
} catch (Exception $error) {
    $response = $error->getMessage();
    $code = $error->getCode();
    JsonResponse::error($response, $code);
}
