<?php
include '../../pgsqlConnection.php';
include '../../security.php';
include 'accounts.php';

$query = new querysAccount();
$accounts = new accounts();
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        $parameterValidate = prepareParameterGet();

        if ($parameterValidate === true) {
            $id = $_GET['id'];
            if ($id == 'alls') {
                $result = $accounts->getAccountsAlls();
            }
            else $result = $accounts->getAccountById($id);
        }
        else {
            echo $parameterValidate;
            break;
        }

        echo $result;
    break;

    case 'PUT':
    $parameterValidate = prepareParameter();

    if ($parameterValidate === true) {
            parse_str(file_get_contents('php://input'), $_PUT);
            $result = $accounts->signUp($_PUT);
        }
        else {
            echo $parameterValidate;
            break;
        }

        echo $result;
    break;

    case 'POST':
        $parameterValidate = prepareParameter();

        if ($parameterValidate == true) {
            $result = $accounts->signIn($_POST);
        }
        else {
            echo $parameterValidate;
            break;
        }

        echo $result;
    break;

    case 'DELETE':
        // parse_str(file_get_contents('php://input'), $_DELETE);
        echo 'en construccion';
    break;
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

function prepareParameter() {
    if ($_POST) {
        $method = $_POST;
        $arraySet = ['email', 'password'];
    }
    else {
        parse_str(file_get_contents('php://input'), $_PUT);
        $method = $_PUT;
        $arraySet = ['username', 'email', 'password'];
    }

    foreach ($arraySet as $defaultKey) {
        $keyNotFound = true;
        foreach ($method as $entryKey => $value) {
            if ($defaultKey === $entryKey) {
                if (!empty($value)) {
                    if ($entryKey == 'username') {

                    }
                    else if ($entryKey == 'email') {
                        $security = new security();
                        $emailNew = $security->validateEmail($value);
                        $method['email'] = $emailNew;
                    }
                    else if ($entryKey == 'password') {

                    }
                    $keyNotFound = false;
                    break;
                }
                else {
                    $_arrayResponse = ['Error' => "Parameter: {$key} is empty"];
                    return responseError($_arrayResponse);
                }
            }
        }
        if ($keyNotFound) {
            $_arrayResponse = ['Error' => "Parameter: {$key} not found"];
            return responseError($_arrayResponse);
        }
    }

    return true;
}

function responseError($_arrayResponse) {
    http_response_code(400);
    $arrayResponse['userAccount'][] = $_arrayResponse;

    return json_encode($arrayResponse);
}
?>