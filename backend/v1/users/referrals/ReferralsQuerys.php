<?php

class ReferralsQuerys extends PgSqlConnection
{
    public static function selectAlls()
    {
        $sql = "SELECT referrals_data
                FROM referrals
                WHERE user_id = ?";

        $query = self::connection()->prepare($sql);
        $query->execute([
            $_GET['id']
        ]);
        return GeneralMethods::processSelect($query);
    }

    public static function selectById()
    {
        $sql = "SELECT referrals_data
                FROM referrals
                WHERE user_id = ?";

        $query = self::connection()->prepare($sql);
        $query->execute([
            $_GET['id']
        ]);
        return GeneralMethods::processSelect($query);
    }

    public static function select($where, $value)
    {
        $sql = "SELECT *
                FROM referrals
                WHERE " . $where . " = ?";

        $query = self::connection()->prepare($sql);
        $query->execute([
            $value
        ]);
        return GeneralMethods::processSelect($query);
    }

    public static function selectByCode()
    {
        $sql = "SELECT referrals_data
                FROM referrals
                WHERE invite_code = ?";

        $query = self::connection()->prepare($sql);
        $query->execute([
            $_REQUEST['invite-code']
        ]);
        return GeneralMethods::processSelect($query);
    }

    public static function insert()
    {
        $sql = "INSERT INTO referrals (user_id)
                VALUES (?)";

        $query = self::connection()->prepare($sql);
        $query->execute([
            $_GET['id']
        ]);
        return GeneralMethods::processQuery($query);
    }

    public static function updateCode($code)
    {
        $sql = "UPDATE referrals
                SET invite_code = ?, code_create_date = ?
                WHERE user_id = ?";

        $query = self::connection()->prepare($sql);
        $query->execute([
            $code,
            date('Y-m-d h:i:s'),
            $_GET['id']
        ]);
        return GeneralMethods::processQuery($query);
    }

    public static function update($where, $value, $referrals)
    {
        $sql = "UPDATE referrals
                SET referrals_data = ?
                WHERE " . $where . " = ?";

        $query = self::connection()->prepare($sql);
        $query->execute([
            $referrals,
            $value
        ]);
        return GeneralMethods::processQuery($query);
    }

    public static function delete()
    {
        $sql = "DELETE FROM referrals
                WHERE user_id = ?";

        $query = self::connection()->prepare($sql);
        $query->execute([
            $_GET['id']
        ]);
        return GeneralMethods::processQuery($query);
    }
}
