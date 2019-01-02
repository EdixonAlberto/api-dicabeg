<?php

class querysAccount {

    function __construct() {

    }

    function insertAccount($username, $email, $password) {
        $sql = "INSERT INTO users_accounts (username, email, password)
                VALUES (?, ?, ?)";

        $query = $this->data->connect()->prepare($sql);
        $query->execute([
                $username,
                $email,
                $password,
            ]);
        $arrayInfo = $query->errorInfo();

        return $arrayInfo;
    }
}
?>