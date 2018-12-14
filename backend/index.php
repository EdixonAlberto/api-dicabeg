<?php
    include 'user.php';

    // $comando = $_GET['user'];
    // $usuarios = new userQuerys();
    
    // if  ($comando == 'getAlls') {
    //     echo $usuarios->get();
    // }
    // else {
    //     echo 'command not valid';
    // }
    $usuarios = new userQuerys();
    echo $usuarios->get();
?>