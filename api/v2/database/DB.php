<?php

namespace V2\Database;

class DB
{
    private $engine;
    private $name;
    private $host;
    private $user;
    private $password;

    public function __construct($engine)
    {
        $url = $this->decomposeURL(DATABASE_URL);
        $this->engine = $engine;
        $this->name = trim($url['path'], '/');
        $this->host = $url['host'];
        $this->user = $url['user'];
        $this->password = $url['pass'];
    }

    protected function connect()
    {
        $connection = $this->engine . ':dbname=' . $this->name . ';host=' . $this->host;
        return new \PDO($connection, $this->user, $this->password);
    }

    private function decomposeURL($url)
    {
        $urlCorrect = $url; // TODO: realizar un prematch, para validar la composicion de la URI
        if ($urlCorrect) return parse_url($urlCorrect);
        else throw new \Exception('Environment varable do not found', 500);
    }
}
