<?php

// Tools
require_once '../tools/db/PgSqlConnection.php';
require_once '../tools/Validations.php';
require_once '../tools/Security.php';
require_once '../tools/GeneralMethods.php';
require_once '../tools/JsonResponse.php';

// Resource
require_once '../users/UsersQuerys.php';
require_once './Sessions.php';

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
            // Validations::parameters('sessions');
            Sessions::createSession();
            break;

        case 'PATCH':
            // Validations::parameters('sessions');
            Sessions::updateSession();
            break;

        case 'DELETE':
            Sessions::removeSession();
            break;
    }
} catch (Exception $error) {
    $response = $error->getMessage();
    $code = $error->getCode();
    JsonResponse::error($response, $code);
}
