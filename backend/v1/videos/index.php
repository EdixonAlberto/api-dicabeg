<?php

// Tools
require_once '../tools/db/PgSqlConnection.php';
require_once '../tools/Validations.php';
require_once '../tools/Security.php';
require_once '../tools/GeneralMethods.php';
require_once '../tools/JsonResponse.php';

// Resource
require_once '../sessions/Sessions.php';
require_once 'Videos.php';

$method = $_SERVER['REQUEST_METHOD'];
parse_str(file_get_contents('php://input'), $_REQUEST);

try {
    Validations::id();
    // Sessions::verifySession();

    switch ($method) {
        case 'GET':
            ($_GET['id'] == 'alls') ?
                Videos::getVideosAlls() :
                Videos::getVideosById();
            break;
    }
} catch (Exception $error) {
    $arrayResponse['Videos'][] = ['Error' => $error->getMessage()];
    http_response_code($error->getCode());
    echo json_encode($arrayResponse);
}
