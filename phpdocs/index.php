<?php
    include 'apiUser.php';

    $email = $_GET['email'];
    $pass = $_GET['pass'];

    $usuarios = new apiUser();
    $listajs = $usuarios->newUser($email, $pass);
    print_r($listajs);
?>