<?php

// Tools
require_once '../../tools/db/PgSqlConnection.php';
require_once '../../tools/Validations.php';
require_once '../../tools/GeneralMethods.php';
require_once '../../tools/Gui.php';
require_once '../../tools/Security.php';

// Resource
require_once '../../sessions/Sessions.php';
require_once '../data/DataQuerys.php';
require_once 'Referrals.php';

$method = $_SERVER['REQUEST_METHOD'];
parse_str(file_get_contents('php://input'), $_REQUEST);

try {
    Validations::id();
    Sessions::verifySession();

    switch ($method) {
        case 'GET':
            $response = ($_GET['id_2'] == 'alls') ?
                Referrals::getReferralsAlls() :
                Referrals::getReferralsById();

            if ($response) {
            } else throw new Exception('Referrals does not exist', 400);
            break;

        case 'PUT':
            // Validations::parameters('Referrals');
            $response = Referrals::insertReferrals();
            break;

        case 'DELETE':
            // Validations::parameters('Referrals');
            $response = Referrals::deleteReferrals();
            break;
    }
    http_response_ok($response);
} catch (Exception $error) {
    $arrayResponse['referrals'][] = ['Error' => $error->getMessage()];
    http_response_code($error->getCode());
    echo json_encode($arrayResponse);
}

function http_response_ok($response)
{
    http_response_code(200);
    $arrayResponse['referrals'] = $response;
    echo json_encode($arrayResponse);
}
