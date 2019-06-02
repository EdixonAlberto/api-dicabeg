<?php

namespace V2\Database;

use PDO;
use Exception;
use PDOException;

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
        try {
            $connection = "{$this->engine}:dbname={$this->name} host={$this->host}";
            return new PDO($connection, $this->user, $this->password);

        } catch (PDOException $error) {
            $err = utf8_decode($error->getMessage());
            throw new Exception($err, 500);
        }
    }

    private function decomposeURL($url)
    {
        $pattern = '|([a-z]+)://([a-z0-9]+):(.*)@(.*):([0-9]+)/([a-z0-9]+)|';
        $urlCorrect = preg_match($pattern, $url);

        if ($urlCorrect) return parse_url($url);
        else throw new Exception('incorrect coneccion string', 500);
    }
}
