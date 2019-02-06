<?php

require_once '../tools/db/PgSqlConnection.php';
require_once '../tools/Validations.php';
require_once '../tools/Security.php';
require_once '../tools/GeneralMethods.php';
require_once '../sessions/Sessions.php';
require_once 'Videos.php';

$method = $_SERVER['REQUEST_METHOD'];
parse_str(file_get_contents('php://input'), $_REQUEST);
// var_dump($_SERVER);
// die;

try {
    // Sessions::verifySession(); //TODO: Agregar verificacion para todo tipo de recurso

    switch ($method) {
        case 'GET':
            Validations::id();
            $response = ($_GET['id'] == 'alls') ?
                Videos::getVideosAlls() :
                Videos::getVideosById();
            http_response_ok($response);
            break;
    }
} catch (Exception $error) {
    $arrayResponse['Videos'][] = ['Error' => $error->getMessage()];
    http_response_code($error->getCode());
    echo json_encode($arrayResponse);
}

function http_response_ok($response)
{
    http_response_code(200);
    $arrayResponse['Videos'] = $response;
    echo json_encode($arrayResponse);
}
