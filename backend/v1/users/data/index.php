<?php

require_once '../../tools/db/PgSqlConnection.php';
require_once '../../tools/Validations.php';
require_once 'Data.php';

$method = $_SERVER['REQUEST_METHOD'];
parse_str(file_get_contents('php://input'), $_REQUEST['parameters']);

try {
    switch ($method) {
        case 'GET':
            Validations::id();
            $result = ($_REQUEST['id'] == 'alls') ?
                Data::getDataAlls() :
                Data::getDataById();
            http_response_ok($result);
            break;

        case 'PATCH':
            Validations::id();
            Validations::parameters('data');
            $result = Data::updateData();
            http_response_ok($result);
            break;
    }
} catch (Exception $error) {
    $arrayResponse['usersData'][] = ['Error' => $error->getMessage()];
    http_response_code($error->getCode());
    echo json_encode($arrayResponse);
}

function http_response_ok($response)
{
    http_response_code(200);
    $arrayResponse['usersData'] = $response;
    echo json_encode($arrayResponse);
}
