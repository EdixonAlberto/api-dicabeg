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
        $emptyMethod = empty($_GET);
        
        if ($emptyMethod) {
            echo $user->message('Use method POST: user - email or GET: ?email=user email ');
        }
        else {
            $email = $_GET['email'];

            if ($email == 'alls') {
                $email = $_GET['email'];
                $result = $user->getUserAlls();    
            }
            elseif (strpos($email, '@')) {
                $email = $_GET['email'];
                $result = $user->getUserByEmail($email);
            }
            echo $result;
        }  
    }
?>