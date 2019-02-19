<?php

// Tools
require_once '../tools/db/PgSqlConnection.php';
require_once '../tools/Validations.php';
require_once '../tools/GeneralMethods.php';
require_once '../tools/Gui.php';
require_once '../tools/Security.php';
require_once '../tools/JsonResponse.php';

// Resource
require_once '../sessions/Sessions.php';
require_once './referrals/Referrals.php';
require_once './Users.php';

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
                Users::getUsersAlls() :
                Users::getUserById();
            break;

        case 'POST':
            // Validations::parameters('Users');
            Users::createUser();
            break;

        case 'PATCH':
            // Validations::parameters('Users');
            Users::updateUser();
            break;

        case 'DELETE':
            // Validations::parameters('Users');
            Users::deleteUser();
            break;
    }
} catch (Exception $error) {
    $response = $error->getMessage();
    $code = $error->getCode();
    JsonResponse::error($response, $code);
}
