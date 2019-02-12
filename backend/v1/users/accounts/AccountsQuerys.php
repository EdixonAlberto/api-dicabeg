<?php

class AccountsQuerys extends PgSqlConnection
{
    public static function selectAlls()
    {
        $sql = "SELECT * FROM users_accounts";

        $query = self::connection()->prepare($sql);
        $query->execute();

        return $query;
    }

    public static function selectById($value, $key = 'id')
    {
        // TODO: Pensar mejor si esta consulta se reduce solo a select id
        switch ($key) {
            case 'id':
                $sql = "SELECT * FROM users_accounts
                        WHERE user_id = ?";
                break;

            case 'email':
                $sql = "SELECT * FROM users_accounts
                        WHERE email = ?";
                break;
        }

        $query = self::connection()->prepare($sql);
        $query->execute([
            $value
        ]);

        return $query;
    }

    public static function insert($arraySet)
    {
        $sql = "INSERT INTO users_accounts (user_id, email, password, create_date)
                VALUES (?, ?, ?, ?)";

        $query = self::connection()->prepare($sql);
        $query->execute([
            $_GET['id'],
            $arraySet[0],
            $arraySet[1],
            date('Y-d-m')
        ]);

        return $query;
    }

    public static function update($key, $value)
    {
        // TODO: Como usar una sola sentencia sql con el uso de variables
        if ($key == 'email') {
            $sql = "UPDATE users_accounts
                    SET email = ?, update_date = ?
                    WHERE user_id = ?";

        } else {
            $sql = "UPDATE users_accounts
                    SET password = ?, update_date = ?
                    WHERE user_id = ?";
        }

        $query = self::connection()->prepare($sql);
        $query->execute([
            $value,
            $_GET['id'],
            date('Y-d-m')
        ]);

        return $query;
    }

    public static function delete()
    {
        $sql = "DELETE FROM users_accounts
                WHERE user_id = ?";

        $query = self::connection()->prepare($sql);
        $query->execute([
            $_GET['id']
        ]);

        return $query;
    }
}
