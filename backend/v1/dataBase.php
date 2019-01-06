<?php

class dataBase {
    private $engine;
    private $name;
    private $host;
    private $user;
    private $password;

    public function __construct($engine ,$environmentVariable) {
        $url = $this->decomposeURL($environmentVariable);

        if ($url) {
            $this->engine   = $engine;
            $this->name     = trim($url['path'], '/');
            $this->host     = $url['host'];
            $this->user     = $url['user'];
            $this->password = $url['pass'];
        }
        else {
            $var ='Environment varable do not found';
            die($var);
        }
    }

    function decomposeURL($url) {
        $urlCorrect = getenv($url);

        if ($urlCorrect) {
            return parse_url(getenv($url));
        }
        else {
            return false;
        }
    }

    function connect() {
        try {
            $stringConnection = $this->engine . ':dbname=' . $this->name . ';host=' . $this->host;
            $newConnection = new PDO($stringConnection, $this->user, $this->password);
            return $newConnection;

        }
        catch (PDOException $error) {
            return '[Connection error in the database] ERROR = ' . $error;
        }
    }
}
?>