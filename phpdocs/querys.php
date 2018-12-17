<?php
    include 'database.php';

    class querys extends dataBase {
        private $data;

        public function __construct() {
            // $variable = "mysql://root:@localhost:3306/db_dicabeg";
            $uri = 'postgres://xwbsioiegvytgz:0eaa6d4b347bac85367e3623092bc6c68e81c4abb6a7303971f90f2c88ffb059@ec2-54-227-249-201.compute-1.amazonaws.com:5432/d4r8bbusqmajl7';
            $this->data = new dataBase('pgsql', $uri);
        }

        function getBy($email) {
            $sql = "SELECT * FROM users
                    WHERE email = :email";

            $query = $this->data->connect()->prepare($sql);
            $query->execute([':email' => $email]);
            return $query;
        }

        function signUp($email, $pass) {
            $sql = "INSERT INTO users(email, password)
                    VALUES (:email, :pass)";

            $query = $this->data->connect()->prepare($sql);
            $query->execute([':email' => $email, ':pass' => $pass]);
            return $query->errorInfo();
        }

        // function 0426 3070 365
    }
?>