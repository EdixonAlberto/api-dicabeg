<?php

require_once '../../tools/db/PgSqlConnection.php';
require_once '../../tools/Validations.php';
require_once '../../tools/Security.php';
require_once '../../tools/GeneralMethods.php';
require_once '../../sessions/Sessions.php';
require_once 'Accounts.php';

$method = $_SERVER['REQUEST_METHOD'];
parse_str(file_get_contents('php://input'), $_REQUEST);
// var_dump($_SERVER);
// die;

try {
    if ($method != 'PUT' and $_GET['id'] != 'alls') Sessions::verifySession();

    switch ($method) {
        case 'GET':
            Validations::id();
            $response = ($_GET['id'] == 'alls') ?
                Accounts::getAccountsAlls() :
                Accounts::getAccountsById();
            http_response_ok($response);
            break;

        case 'PUT':
            Validations::parameters('accounts');
            $response = Accounts::insertAccount();
            http_response_ok($response);
            break;

        case 'PATCH':
            Validations::id();
            Validations::parameters('accounts');
            $response = Accounts::updateAccount();
            http_response_ok($response);
            break;

        case 'DELETE':
            Validations::id();
            Validations::parameters('accounts');
            $response = Accounts::deleteAccount();
            http_response_ok($response);
            break;
    }
} catch (Exception $error) {
    $arrayResponse['usersAccounts'][] = ['Error' => $error->getMessage()];
    http_response_code($error->getCode()); //TODO: Revisar como funciona este metodo;
    echo json_encode($arrayResponse);
}

function http_response_ok($response)
{
    http_response_code(200);
    $arrayResponse['usersAccounts'] = $response;
    echo json_encode($arrayResponse);
}
