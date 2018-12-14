<?php
    class dataBase {
        private $engine;
        private $name;
        private $host;
        private $user;
        private $password;

        public function __construct($engine) {
            $this->engine   = $engine;
            switch ($engine) {
                case 'mysql':
                    $this->name     = 'db_dicabeg';
                    $this->host     = 'localhost';
                    $this->user     = 'root';
                    $this->password = '';
                break;
                case 'pgsql':
                    $this->name     = 'd4r8bbusqmajl7';
                    $this->host     = 'ec2-54-227-249-201.compute-1.amazonaws.com';
                    $this->user     = 'xwbsioiegvytgz';
                    $this->password = '0eaa6d4b347bac85367e3623092bc6c68e81c4abb6a7303971f90f2c88ffb059';
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