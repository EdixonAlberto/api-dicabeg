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
        switch ($key) {
            case 'id':
                $sql = "SELECT * FROM users_accounts
                        WHERE user_id = ?";
                break;

            case 'email':
                $sql = "SELECT * FROM users_accounts
                        WHERE email = ?";
                break;

            case 'password':
                $sql = "SELECT * FROM users_accounts
                        WHERE password = ?";
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
        $sql = "INSERT INTO users_accounts (user_id, email, password, create_date, update_date)
                VALUES (?, ?, ?, ?, ?)";

        $query = self::connection()->prepare($sql);
        $query->execute([
            $_GET['id'],
            $arraySet[0],
            $arraySet[1],
            $arraySet[2],
            $arraySet[3]
        ]);

        return $query;
    }

    public static function update($column, $arraySet)
    {
        if ($column == 'email') {
            $sql = "UPDATE users_accounts
                    SET email = ?, update_date = ?
                    WHERE user_id = ?";

        } else if ($column == 'password') {
            $sql = "UPDATE users_accounts
                    SET password = ?, update_date = ?
                    WHERE user_id = ?";
        }

        $query = self::connection()->prepare($sql);
        $query->execute([
            $arraySet[0],
            $arraySet[1],
            $_GET['id'],
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
