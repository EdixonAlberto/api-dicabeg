<?php
    include 'database.php';

    class querys extends dataBase {
        private $data;

        public function __construct() {
            $this->data = new dataBase();
        }

        function getAll() {
            $sql = "SELECT * FROM users";

            $query = $this->data->connect()->prepare($sql);
            $query->execute();

            return $query;
        }

        function getBy($varEmail) {
            $sql = "SELECT * FROM users
                    WHERE email = ?";

            $query = $this->data->connect()->prepare($sql);
            $query->execute([
                    $varEmail
                ]);

            return $query;
        }

        function insert($varEmail, $varPass) {
            $sql = "INSERT INTO users (email, password)
                    VALUES (?, ?)";

            $query = $this->data->connect()->prepare($sql);
            $query->execute([
                    $varEmail,
                    $varPass
                ]);
            $arrayInfo = $query->errorInfo();

            return $arrayInfo;
        }
    }
?>