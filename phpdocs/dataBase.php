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

        function decomposeURL($variable) {
            return parse_url($variable);
            // return parse_url(getenv($variable));
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