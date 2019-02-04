<?php

require_once '../../tools/db/PgSqlConnection.php';
require_once '../../tools/Validations.php';
require_once 'Sessions.php';

$method = $_SERVER['REQUEST_METHOD'];
parse_str(file_get_contents('php://input'), $_REQUEST);
// var_dump($method, $_GET, $_REQUEST);
// die;

try {
    switch ($method) {
        case 'GET':
            Validations::id();
            $response = ($_GET['id'] == 'alls') ?
                Sessions::getSessionsAlls() :
                Sessions::getSessionsById();
            http_response_ok($response);
            break;

        case 'PUT':
            // Validations::parameters('sessions');
            $response = Sessions::createSession();
            http_response_ok($response);
            break;

        case 'DELETE':
            // Validations::parameters('sessions');
            $response = Sessions::removeSession();
            http_response_ok($response);
            break;
    }

} catch (Exception $error) {
    $arrayResponse['sessions'][] = ['Error' => $error->getMessage()];
    http_response_code($error->getCode());
    echo json_encode($arrayResponse);
}

function http_response_ok($response)
{
    http_response_code(200);
    $arrayResponse['sessions'] = $response;
    echo json_encode($arrayResponse);
}