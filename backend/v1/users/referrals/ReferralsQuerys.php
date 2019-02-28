<?php

namespace V1\Referrals;

use PDO;
use Db\PgSqlConnection;
use Tools\GeneralMethods;

class ReferralsQuerys extends PgSqlConnection
{
    public static function search()
    {
        $sql = "SELECT COUNT(user_id)
                FROM referrals
                WHERE user_id = ?";

        $query = self::connection()->prepare($sql);
        $query->execute([
            $_GET['id']
        ]);
        $result = $query->fetch(PDO::FETCH_OBJ);
        return $result->count;
    }

    public static function selectByCode($field)
    {
        $sql = "SELECT " . $field
            . " FROM referrals
                WHERE invite_code = ?";

        $query = self::connection()->prepare($sql);
        $query->execute([
            $_REQUEST['invite_code']
        ]);
        return $query->fetch(PDO::FETCH_OBJ);
    }

    public static function selectAlls()
    {
        $sql = "SELECT referrals_data
                FROM referrals
                WHERE user_id = ?";

        $query = self::connection()->prepare($sql);
        $query->execute([
            $_GET['id']
        ]);
        $referrals = GeneralMethods::processSelect($query);
        return json_decode($referrals->referrals_data);
    }

    public static function insert($inviteCode)
    {
        $sql = "INSERT INTO referrals (user_id, invite_code)
                VALUES (?, ?)";

        $query = self::connection()->prepare($sql);
        $query->execute([
            $_GET['id'],
            $inviteCode
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
