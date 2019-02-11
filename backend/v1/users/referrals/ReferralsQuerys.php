<?php

class ReferralsQuerys extends PgSqlConnection
{
    public static function selectAlls()
    {
        $sql = "SELECT referrals_json AS json
                FROM referrals
                WHERE user_id = ?";

        $query = self::connection()->prepare($sql);
        $query->execute([
            $_GET['id']
        ]);

        return $query;
    }

    public static function insert()
    {
        $sql = "INSERT INTO referrals (user_id)
                VALUES (?)";

        $query = self::connection()->prepare($sql);
        $query->execute([
            $_GET['id'],
        ]);

        return $query;
    }

    public static function update($referrals)
    {
        $sql = "UPDATE referrals
                SET referrals_json = ?
                WHERE user_id = ?";

        $query = self::connection()->prepare($sql);
        $query->execute([
            $referrals,
            $_GET['id']
        ]);

        return $query;
    }

    public static function delete()
    {
        $sql = "DELETE FROM referrals
                WHERE user_id = ?";

        $query = self::connection()->prepare($sql);
        $query->execute([
            $_GET['id']
        ]);

        return $query;
    }
}
