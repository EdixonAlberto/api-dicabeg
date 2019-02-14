<?php

// Tools
require_once '../tools/db/PgSqlConnection.php';
require_once '../tools/Validations.php';
require_once '../tools/Security.php';
require_once '../tools/GeneralMethods.php';
require_once '../tools/JsonResponse.php';

// Resource
require_once '../users/accounts/AccountsQuerys.php';
require_once '../users/data/Data.php';
require_once 'Sessions.php';

$method = $_SERVER['REQUEST_METHOD'];
parse_str(file_get_contents('php://input'), $_REQUEST);

try {
    switch ($method) {
        case 'GET':
            Validations::id();
            $result = ($_GET['id'] == 'alls') ?
                Sessions::getSessionsAlls() :
                Sessions::getSessionsById();

            $response['sessions'][] = $result;
            JsonResponse::send($response);
            break;

        case 'POST':
            // Validations::parameters('sessions');
            Sessions::createSession();
            break;

        case 'DELETE':
            Sessions::verifySession();
            Sessions::removeSession();
            break;
    }
} catch (Exception $error) {
    $response = $error->getMessage();
    $code = $error->getCode();
    JsonResponse::error('session', $response, $code);
}
