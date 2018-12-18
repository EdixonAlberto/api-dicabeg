<?php
    class dataBase {
        private $engine;
        private $name;
        private $host;
        private $user;
        private $password;

        public function __construct($engine, $environmentVariable) {
            $url = $this->decomposeURL($environmentVariable);
            $this->engine   = $engine;
            $this->name     = trim($url['path'], '/');
            $this->host     = $url['host'];
            $this->user     = $url['user'];
            $this->password = $url['pass'];
        }

        function decomposeURL($url) {
            return parse_url(getenv($url));
        }

        function connect() {
            try {
                $stringConnection = $this->engine . ':dbname=' . $this->name . ';host=' . $this->host;
                $newConnection = new PDO($stringConnection, $this->user, $this->password);
                return $newConnection;
                
            } catch (PDOException $error) {
                echo 'connection error in the database: ' . $error;
            }
        }
    }
?>