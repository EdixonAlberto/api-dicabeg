<?php
    require('../vendor/autoload.php');
    include_once 'user.php';
    
    // MODO DE USO
    $usuario = new userQuerys();
    $verUsuarios = $usuario->get();
    print_r($verUsuarios);

    $valor = _GE
?>