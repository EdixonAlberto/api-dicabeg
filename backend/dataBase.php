<?php
    class dataBase {
        private $engine;
        private $name;
        private $host;
        private $user;
        private $password;

        public function __construct() {
            $url = $this->decomposeURL();
            $this->engine   = 'pgsql';
            $this->name     = trim($url['path'], '/');
            $this->host     = $url['host'];
            $this->user     = $url['user'];
            $this->password = $url['pass'];
        }

        function decomposeURL() {
            // return parse_url($variable);
            return parse_url(getenv('DATABASE_URL'));
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