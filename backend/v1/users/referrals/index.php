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
require_once '../UsersQuerys.php';
require_once './Referrals.php';

$method = $_SERVER['REQUEST_METHOD'];
parse_str(file_get_contents('php://input'), $_REQUEST);

try {
    Validations::id();
    Sessions::verifySession();

    switch ($method) {
        case 'GET':
            ($_GET['id_2'] == 'alls') ?
                Referrals::getReferralsAlls() :
                Referrals::getReferredById();
            break;

        case 'DELETE':
            // Validations::parameters('Referrals');
            Referrals::removeReferred();
            break;
    }
} catch (Exception $error) {
    $response = $error->getMessage();
    $code = $error->getCode();
    JsonResponse::error($response, $code);
}
