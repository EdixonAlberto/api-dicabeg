<?php

namespace Db;

use PDO;

class DataBase
{
    private $engine;
    private $name;
    private $host;
    private $user;
    private $password;

    public function __construct($engine, $environmentVariable)
    {
        $url = $this->decomposeURL($environmentVariable);

        if ($url) {
            $this->engine = $engine;
            $this->name = trim($url['path'], '/');
            $this->host = $url['host'];
            $this->user = $url['user'];
            $this->password = $url['pass'];
        } else {
            $var = 'Environment varable do not found';
            die($var);
        }
    }

    private function decomposeURL($url)
    {
        $urlCorrect = getenv($url);

        if ($urlCorrect) {
            return parse_url(getenv($url));
        } else {
            return false;
        }
    }

    public function connect()
    {
        try {
            $connection = $this->engine . ':dbname=' . $this->name . ';host=' . $this->host;
            $newConnection = new PDO($connection, $this->user, $this->password);
            return $newConnection;
        } catch (Exception $error) {
            $arrayResponse[] = [
                'Type' => '[Connection error in the database]',
                'Error' => $error
            ];
            // var_dump($arrayResponse);
        }
        // TODO: Agregar codigo para cerrar conexion!
    }
}
