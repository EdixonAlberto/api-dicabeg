<?php

class AccountsQuerys extends PgSqlConnection
{
    public static function selectAlls()
    {
        $sql = "SELECT *
                FROM users_accounts";

        $query = self::connection()->prepare($sql);
        $query->execute();
        return GeneralMethods::processSelect($query);
    }

    public static function selectById($fields = '*')
    {
        $sql = "SELECT " . $fields
            . " FROM users_accounts
                WHERE user_id = ?";

        $query = self::connection()->prepare($sql);
        $query->execute([
            $_GET['id']
        ]);
        return GeneralMethods::processSelect($query);
    }

    public static function select($where, $value, $fields = '*')
    {
        $sql = "SELECT " . $fields
            . " FROM users_accounts
                WHERE " . $where . " = ?";

        $query = self::connection()->prepare($sql);
        $query->execute([
            $value
        ]);
        return GeneralMethods::processSelect($query);
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
            date('Y-m-d h:i:s')
        ]);
        return GeneralMethods::processQuery($query);
    }

    public static function update($where, $value)
    {
        $sql = "UPDATE users_accounts
                SET " . $where . " = ?, update_date = ?
                WHERE user_id = ?";

        $query = self::connection()->prepare($sql);
        $query->execute([
            $value,
            date('Y-m-d h:i:s'),
            $_GET['id']
        ]);
        return GeneralMethods::processQuery($query);
    }

    public static function delete()
    {
        $sql = "DELETE FROM users_accounts
                WHERE user_id = ?";

        $query = self::connection()->prepare($sql);
        $query->execute([
            $_GET['id']
        ]);
        return GeneralMethods::processQuery($query);
    }
}
