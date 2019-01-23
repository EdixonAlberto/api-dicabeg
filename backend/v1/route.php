<?php

var_dump($_SERVER); die;

$uri = $_SERVER['REQUEST_URI'];
$position = strrpos($uri, '/') + 1;
$id = substr($uri, $position);

echo 'Direccion: ' . $uri . ', Parametro: ' . $id;

$descomponer = array();



?>