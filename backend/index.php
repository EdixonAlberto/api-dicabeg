<?php
    include 'user.php';

    $comando = $_GET['user'];
    $usuarios = new userQuerys();
    
    if  ($comando == 'getAlls') {
        $listajs = $usuarios->get();
        echo $listajs;
    }
    else {
        echo 'command not valid';
    }
?>