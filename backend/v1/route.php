<?php

// 'REQUEST_URI' => string '/v1/users/2' (length=31)

$uri = $_SERVER['REQUEST_URI'];

var_dump($uri);

while (strlen($uri) > 3) {
    $position = strpos($uri, '/', 1);
    $dirs[] = substr($uri, 1, $position);
    $uri = substr($uri, $position);
}
$dirs[] = trim($uri, '/');

var_dump($dirs);

die;

/*
Pasos basicos para hacer funcionar el enrutamiento de direcciones http

descomponer uri
guardar en array
recorrer array
enrutar o direccinar recursos
capturar parametros
ejecutar el code correspondiente
*/