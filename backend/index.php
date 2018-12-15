<?php
    include 'user.php';

    $comando = $_GET['user'];
    $usuarios = new userQuerys();
    
    if  ($comando == 'getAlls') {
        $listajs = $usuarios->get();
        echo $listajs;
    }
    else {
        prinf_r(parse_url('DATABASE_URL')); echo "<br />";
        echo 'command not valid';
    }
?>