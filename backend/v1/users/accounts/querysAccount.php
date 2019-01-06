<?php

class querysAccount extends pgsqlConnection {

    function getAll() {
        $sql = "SELECT * FROM users_accounts";

        $query = $this->connection()->prepare($sql);
        $query->execute();

        return $query;
    }

    function getBy($id, $column = null) {
        switch ($column) {
            case 'username':
                $sql = "SELECT * FROM users_accounts
                        WHERE username = ?";
            break;

            case 'password':
                $sql = "SELECT * FROM users_accounts
                        WHERE password = ?";
            break;

            case 'email':
                $sql = "SELECT * FROM users_accounts
                        WHERE email = ?";
            break;

            default:
                $sql = "SELECT * FROM users_accounts
                        WHERE account_id = ?";
        }

        $query = $this->connection()->prepare($sql);
        $query->execute([
                $id
            ]);

        return $query;
    }

    function insertAccount($arraySet) {
        $sql = "INSERT INTO users_accounts (account_id, username, email, password)
                VALUES (?, ?, ?, ?)";

        $query = $this->connection()->prepare($sql);
        $query->execute([
                $arraySet[0],
                $arraySet[1],
                $arraySet[2],
                $arraySet[3]
            ]);

        return $query;
    }

    function update() {

    }

    function delete() {

    }
}
?>