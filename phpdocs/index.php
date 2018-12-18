<?php
    include 'apiUser.php';

    $user = new apiUser();

    if ($_SERVER['RESQUEST_METHOD'] == 'POST') {
        $email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);
        $pass = filter_var($_POST['pass'],  FILTER_SANITIZE_STRING);
        
        $result = $user->signUp($email, $pass);
        echo $result;
    }
    else {
        $method = $_GET['method'];
        $func = $_GET['id'];
        if ($method == 'user') {
            $result = $user->getUserByEmail($func);
        }
        else {
            $result = $user->getUserAlls();
        }
        return $result;  
    }
?>