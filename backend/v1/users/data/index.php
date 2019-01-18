<?php

include '../../pgsqlConnection.php';
include '../../security.php';
include 'data.php';

$data = new data();

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        $parameterValidate = prepareParameterGet();

        if ($parameterValidate === true) {
            $id = $_GET['id'];
            if ($id == 'alls') {
                $result = $data->getDataAlls();
            }
            else $result = $data->getDataById($id);
        }
        else {
            echo $parameterValidate;
            break;
        }

        echo $result;
    break;

    case 'PATCH':
        $parameterValidate = prepareParameterPatch();
        $correctOperation = $data->updatedataById($arrayValidatedPatch);

        if ($correctOperation) {
            $response = $responseRest->prepareResponse($arraySet, $arrayValidatedPatch);
        }

        http_response_code(200);
        echo $response;
    break;

    default:

}

function prepareParameterGet() {
    foreach ($_GET as $key => $value) {
        if ($key === 'id') {
            if (!empty($value)) {
                return true;
            }
            else $_arrayResponse = ['Error' => "Parameter: {$key} is empty"];
        }
        else $_arrayResponse = ['Error' => "Parameter: {$key} not valid"];

        return responseError($_arrayResponse);
    }
}

function prepareParameterPatch() {
    parse_str(file_get_contents('php://input'), $_PATCH);
    $arrayDataParameters = [
        'names',
        'lastnames',
        'age',
        'image',
        'phone',
        'points',
        'referrals'
    ];

    $arrayValidatedPatch = array();

    $countNull = 0;
    foreach ($arrayDataParameters as $defaultKey) {
        $keyFound = false;

        foreach ($_PATCH as $entryKey => $value) {
            if ($defaultKey === $entryKey) {
                if (empty($value)) {
                    $arrayValidatedPatch[] = null;
                    $countNull++;
                }
                else {
                    $security = new security();
                    if ($defaultKey == 'age' or $defaultKey == 'points') {
                        if (!is_numeric($value)) {
                            $_arrayResponse = ['Error' => "Parameter: {$key} is numeric"];
                            die;
                        }
                    }
                    else if ($defaultKey == 'phone') {
                        $value = $security->cleanPhone($value);
                    }
                    $arrayValidatedPatch[] = $value;
                }
                $keyFound = true;
                break;
            }
        }
        if (!$keyFound) {
            $_arrayResponse = ['Error' => "algo"];
        }
    }

    // if (count($countNull) == count($arrayValidatedPatch)) {
    //     echo 'mal'; die;
    // }

    var_dump($arrayValidatedPatch); die;
}

function getData(&$response) {
    $id = $_GET['id'];
    $user = new data();

    if ($id == 'alls') {
        $response = $user->getdataAlls();
    }
    else {
        $response = $user->getdataById($id);
    }
}

function responseError($_arrayResponse) {
    http_response_code(400);
    $arrayResponse['userData'][] = $_arrayResponse;

    return json_encode($arrayResponse);
}
?>