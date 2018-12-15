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

        echo "<br /> ----- <br /> ";
        print_r(parse_url(getenv('DATABASE_URL')));

        echo "<br /> ----- <br /> ";
        print_r(parse_url('DATABASE_URL'));
    }
?>