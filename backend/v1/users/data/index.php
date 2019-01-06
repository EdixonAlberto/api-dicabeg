<?php
include '../../pgsqlConnection.php';
include '../../security.php';
include 'data.php';

$data = new data();
$security = new security();
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
        $arrayValuesValidated = preparedataToUpdate($arraySet);
        $correctOperation = $data->updatedataById($arrayValuesValidated);

        if ($correctOperation) {
            $response = $responseRest->prepareResponse($arraySet, $arrayValuesValidated);
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

function preparedataToUpdate(&$arrayValuesValidated) {
    parse_str(file_get_contents('php://input'), $_PATCH);
    include '../../arraySet.php';

    foreach ($arraySet as $defaultKey) {

        $keyFound = false;
        foreach ($_PATCH as $entryKey => $value) {
            if ($defaultKey === $entryKey) {

                if (empty($value)) {
                    $arrayValuesValidated[] = null;
                }
                else {
                    valueValidate($entryKey);

                }
                $keyFound = true;
                break;
            }
        }

        if (!$keyFound) {
            $arrayValuesValidated[] = null;
        }
    }

    var_dump($arrayValuesValidated);
    die();
}

function valueValidate($key) {
    if ($entryKey === 'phone') {
        $arrayValuesValidated[] = validatePhone($value);
    }
    else {
    }
}

function createData(&$response) {

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

function updateData(&$response) {
    parse_str(file_get_contents('php://input'), $_PATCH);
    foreach ($_PATCH as $value) {
        //if (is)

    }
    $id = $_PATCH['id'];
    $names = $_PATCH['names'];
    // $lastnames = $_PATCH['lastnames'];
    // $age = $_PATCH['age'];
    // $image = $_PATCH['image'];
    // $phone = $_PATCH['phone'];
    // $points = $_PATCH['points'];
    // $referrals = $_PATCH['referrals'];

    $query = new querysData();
    $query->update($id , $names, $lastnames, $age, $image, $phone, $points, $referrals);

    if (is_null($query->errorInfo()[1])) {
        $response = true;
    }
    else {
        echo ($query->errorInfo());
    }
}
?>