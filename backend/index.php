<?php
    include 'apiUser.php';

    $user = new apiUser();
    $method = $_SERVER['REQUEST_METHOD'];

    if ($method == 'POST') {
        $email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);
        $pass = filter_var($_POST['pass'],  FILTER_SANITIZE_STRING);
        
        $result = $user->signUp($email, $pass);
        echo $result;
    }
    else if ($method == 'GET') {
        $email = $_GET['email'];
        if ($email != 'alls') {
            $result = $user->getUserByEmail($email);
        }
        else {
            $result = $user->getUserAlls();
        }
        echo $result;  
    }
    else {
        echo $user->message('debe usar method POST o GET');
    }
?>