<?php

require_once '../Security.php';
require_once 'Users.php';

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        $parameterValidate = prepareParameterGet();
        if ($parameterValidate === true) {
            $id = $_GET['id'];
            if ($id == 'alls') {
                $result = Users::getUsersAlls();
            }
            else $result = Users::getUsersById($id);
        }
        else {
            echo $parameterValidate;
            break;
        }
        echo $result;
    break;

    case 'PUT':
        echo $parameterValidate = prepareParameterPUT(); die;
        if ($parameterValidate === true) {
            parse_str(file_get_contents('php://input'), $_PUT);
            $result = Users::signUp($_PUT);
        }
        else {
            echo $parameterValidate;
            break;
        }
        echo $result;
    break;

    case 'PATCH':
        echo $parameterValidate = prepareParameterPatch(); die;
        if ($parameterValidate === true) {
            parse_str(file_get_contents('php://input'), $_PATCH);
            $result = Users::userUpdate($_PATCH);
        }
        else {
            echo $parameterValidate;
            break;
        }
        echo $result;
    break;

    //FIXME: tengo que comprobar errores y acomodar metodos a staticos, y reacer el code en Users
    case 'POST':
        $parameterValidate = prepareParameter();

        if ($parameterValidate == true) {
            $result = $Users->signIn($_POST);
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

function prepareParameterGET() {
    validateId($_GET, $errorResponse);

    if (!$errorResponse) {
        return true;
    }
    else return responseError($errorResponse);
}

function prepareParameterPUT() {
    parse_str(file_get_contents('php://input'), $_PUT);
    if ($_PUT) {
        validate($_PUT, $errorResponse);
    }
    else $errorResponse = ['Error' => 'No parameter found'];

    if (!$errorResponse) {
        return true;
    }
    else return responseError($errorResponse);
}

function prepareParameterPatch() {
    parse_str(file_get_contents('php://input'), $_PATCH);
    $errorResponse = null;
    if ($_PATCH) {
        // 1° parametro
        validateId($_PATCH, $errorResponse);
        // 2° parametro
        if (!$errorResponse) {
            validate($_PATCH, $errorResponse);
        }
    }
    else $errorResponse = ['Error' => 'No parameter found'];

    if (!$errorResponse) {
        return true;
    }
    else return responseError($errorResponse);
}

// TODO:
function prepareParameterPOST() {
}

//TODO:
function prepareParameterDELETE() {
}


// var_dump($resul); die;


function validateId($method, &$errorResponse) {
    if (isset($method['id'])) {
        $id = $method['id'];
        if (!empty($id)) {
            if (strlen($id) != 36) {
                $errorResponse = ['Error' => 'Parameter: id is incorrect'];
            }
        }
        else $errorResponse = ['Error' => 'Parameter: id is empty'];
    }
    else $errorResponse = ['Error' => "Parameter: id not found"];
}

function validate($method, &$errorResponse) {
    if (isset($method['email'])) {
        $email = $method['email'];
        if (!empty($email)) { //TODO:
            // $emailNew = Security::validateEmail($email);
            // $method['email'] = $emailNew;
        }
        else return $errorResponse = ['Error' => 'Parameter: email is empty'];
    }
    else return $errorResponse = ['Error' => "Parameter: email not found"];

    if (isset($method['password'])) {
        $pass = $method['password'];
        if (!empty($pass)) { //TODO:
            // validar
        }
        else $errorResponse = ['Error' => 'Parameter: password is empty'];
    }
    else $errorResponse = ['Error' => "Parameter: password not found"];
}

function responseError($_arrayResponse) {
    http_response_code(400);
    $arrayResponse['users'][] = $_arrayResponse;

    return json_encode($arrayResponse);
}