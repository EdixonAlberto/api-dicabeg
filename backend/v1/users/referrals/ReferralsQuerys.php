<?php

class ReferralsQuerys extends PgSqlConnection
{
    public static function selectAlls($getError = true)
    {
        $sql = "SELECT referrals_data
                FROM referrals
                WHERE user_id = ?";

        $query = self::connection()->prepare($sql);
        $query->execute([
            $_GET['id']
        ]);
        $referrals = GeneralMethods::processSelect($query, $getError);
        return json_decode($referrals->referrals_data);
    }

    public static function insert($referrals)
    {
        $sql = "INSERT INTO referrals (user_id, referrals_data)
                VALUES (?, ?)";

        $query = self::connection()->prepare($sql);
        $query->execute([
            $_GET['id'],
            $referrals
        ]);
        return GeneralMethods::processQuery($query);
    }

    public static function update($referrals)
    {
        $sql = "UPDATE referrals
                SET referrals_data = ?
                WHERE user_id = ?";

        $query = self::connection()->prepare($sql);
        $query->execute([
            $referrals,
            $_GET['id']
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
        return GeneralMethods::processDelete($query);
    }
}
