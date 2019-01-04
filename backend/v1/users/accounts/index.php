<?php
require '../../dataBase.php';
include '../../security.php';
include '../../responseRest.php';
include '../../generateGui.php';
include '../data/querysData.php';
include 'querysAccount.php';
include 'accounts.php';

$query = new querysAccount();
$accounts = new accounts();
$security = new security();
$responseRest = new responseRest();

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        // checkountParameterGET();

        $accountResult = $accounts->getAccountsAlls();

        echo $accountResult; die;
        var_dump($accountResult); die();

        if ($respoonse) {
            http_response_code(200);
            echo $response;
        }

    break;

    case 'POST':
        // $valuesResult = prepareValuesToSession();
        $valuesResult = true;
        if (!$valuesResult) {
            $httpCode = http_response_code(400);
            $arrayError = [$httpCode, $valuesResult];
            $response = $responseRest->error($arrayError);
            echo $response;
        }
        else {
            $createResult = $accounts->signIn($_POST);

            if ($createResult) {
                http_response_code(200);
                echo $createResult;
            }
            else {
                $httpCode = http_response_code(400);
                $arrayError =  [$httpCode, $createResult];
                $response = $responseRest->error($arrayError);
                echo $response;
            }
        }
    break;

    case 'PUT':
        $valuesResult = prepareValuesToCreate();

        if (!$valuesResult) {
            $httpCode = http_response_code(400);
            $arrayError = [$httpCode, $valuesResult];
            $response = $responseRest->error($arrayError);
            echo $response;
        }
        else {
            parse_str(file_get_contents('php://input'), $_PUT);
            $response = $accounts->signUp($_PUT);
            echo $response;
        }
    break;
}

function prepareValuesToCreate() {
    parse_str(file_get_contents('php://input'), $_PUT);

    $arraySet = ['username', 'email', 'password'];

    foreach ($arraySet as $defaultKey) {

        $keyNotFound = true;
        foreach ($_PUT as $entryKey => $value) {
            if ($defaultKey === $entryKey) {

                if (empty($value)) {
                    return $entryKey . 'is empty';
                    return false;
                }
                if ($entryKey == 'email') {
                    // emailValidate($entryKey);
                }

                $keyNotFound = false;
                break;
            }
        }
        if ($keyNotFound) {
            return $defaultKey . 'not found';
        }
    }
    return true;
}
?>