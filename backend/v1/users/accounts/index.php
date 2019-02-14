<?php

// Tools
require_once '../../tools/db/PgSqlConnection.php';
require_once '../../tools/Validations.php';
require_once '../../tools/GeneralMethods.php';
require_once '../../tools/Gui.php';
require_once '../../tools/Security.php';
require_once '../../tools/JsonResponse.php';

// Resource
require_once '../../sessions/Sessions.php';
require_once '../referrals/Referrals.php';
require_once '../data/Data.php';
require_once 'Accounts.php';

$method = $_SERVER['REQUEST_METHOD'];
parse_str(file_get_contents('php://input'), $_REQUEST);

try {
    if ($method != 'POST' and $_GET['id'] != 'alls') {
        Validations::id();
        Sessions::verifySession();
    }

    switch ($method) {
        case 'GET':
            ($_GET['id'] == 'alls') ?
                Accounts::getAccountsAlls() :
                Accounts::getAccountsById();
            break;

        case 'POST':
            // Validations::parameters('accounts');
            Accounts::createAccount();
            break;

        case 'PATCH':
            // Validations::parameters('accounts');
            Accounts::updateAccount();
            break;

        case 'DELETE':
            // Validations::parameters('accounts');
            Accounts::deleteAccount();
            break;
    }
} catch (Exception $error) {
    $response = $error->getMessage();
    $code = $error->getCode();
    JsonResponse::error('userAccount', $response, $code);
}
