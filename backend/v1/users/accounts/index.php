<?php

require_once '../../tools/db/PgSqlConnection.php';
require_once '../../tools/Validations.php';
require_once 'Accounts.php';

$method = $_SERVER['REQUEST_METHOD'];

// var_dump($method, $_REQUEST);
// die;

try {
    switch ($method) {
        case 'GET':
            Validations::id();
            $result = ($_GET['id'] == 'alls') ?
                Accounts::getAccountsAlls() :
                Accounts::getAccountsById();
            http_response_ok($result);
            break;

        case 'PUT':
            parse_str(file_get_contents('php://input'), $_REQUEST);
            Validations::parameters('accounts');
            $result = Accounts::insertAccount();
            http_response_ok($result);
            break;

        case 'PATCH':
            Validations::id();
            parse_str(file_get_contents('php://input'), $_REQUEST);
            Validations::parameters('accounts');
            $result = Accounts::updateAccount();
            http_response_ok($result);
            break;

        case 'DELETE':
            Validations::id();
            parse_str(file_get_contents('php://input'), $_REQUEST);
            Validations::parameters('accounts');
            $result = Accounts::deleteAccount();
            http_response_ok($result);
            break;
        // case 'POST':
        //     $parameterValidate = prepareParameterPOST();
        //     if ($parameterValidate === true) {
        //         $result = Accounts::signIn($_POST);
        //     } else {
        //         echo $parameterValidate;
        //         break;
        //     }
        //     echo $result;
        //     break;
    }
} catch (Exception $error) {
    $arrayResponse['usersAccounts'][] = ['Error' => $error->getMessage()];
    http_response_code($error->getCode());
    echo json_encode($arrayResponse);
}

function http_response_ok($response)
{
    http_response_code(200);
    $arrayResponse['usersAccounts'] = $response;
    echo json_encode($arrayResponse);
}
