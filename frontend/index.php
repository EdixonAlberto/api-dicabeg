<?php
    include('');

    //uso de la api para consulta
    $usuario = userQuerys();

    echo $usuario->get();
?>