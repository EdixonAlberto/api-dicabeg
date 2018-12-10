<?php
    class dataBase {
        private $engine;
        private $name;
        private $host;
        private $user;
        private $password;

        public function __construct($engine) {
            switch ($engine) {
                case 'mysql':
                    $this->engine   = $engine;
                    $this->name     = 'db_dicabeg';
                    $this->host     = 'localhost';
                    $this->user     = 'root';
                    $this->password = '';
                    break;
                case 'nodejs':
                    # code...
            }
        }

        function connect() {
            try {
                $stringConnection = $this->engine . ':dbname=' . $this->name . ';host=' . $this->host;
                $newConnection = new PDO($stringConnection, $this->user, $this->password);
                return $newConnection;
                
            } catch (PDOException $error) {
                echo"connection error in the database: " . $error;
            }
        }
    }
?>