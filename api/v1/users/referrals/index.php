<?php

require_once __DIR__ . '../../../../../vendor/autoload.php';
\Lib\PhpDotEnv::load();

use Tools\Security;
use Tools\Validations;
use Tools\JsonResponse;
use V1\Users\Referrals\Referrals;

$method = $_SERVER['REQUEST_METHOD'];
parse_str(file_get_contents('php://input'), $_REQUEST);
$_GET ?? Validations::gui();

try {
    Security::verifySession();
    switch ($method) {
        case 'GET':
            ($_GET['id_2'] == 'alls') ?
                Referrals::index() :
                Referrals::show();
            break;

        case 'DELETE':
            Referrals::destroy();
            break;
    }
} catch (Exception $error) {
    $response = $error->getMessage();
    $code = $error->getCode();
    JsonResponse::error($response, $code);
}
