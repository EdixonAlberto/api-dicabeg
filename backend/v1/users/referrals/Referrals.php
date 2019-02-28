<?php

namespace V1\Users\Referrals;

use Db\Querys;
use Exception;
use Tools\JsonResponse;

class Referrals
{
    protected const SET = 'referred_id, create_date';
    protected const TIME = 'Y-m-d H:i:00';

    public static function index()
    {
        $referredQuery = new Querys('referrals');

        $arrayReferrals = $referredQuery->select('user_id', $_GET['id'], self::SET);
        if ($arrayReferrals == false) throw new Exception('not found resourse', 404);

        foreach ($arrayReferrals as $referred) {
            $referred_data = self::getReferredData($referred);
            $_arrayReferrals[] = $referred_data;
        }
        JsonResponse::read('referrals', $_arrayReferrals);
    }

    public static function show()
    {
        $referredQuery = new Querys('referrals');

        $referrals_id = $_GET['id'] . $_GET['id_2'];
        $arrayReferrals = $referredQuery->select('referrals_id', $referrals_id, self::SET);
        if ($arrayReferrals == false) throw new Exception('not found resourse', 404);

        $_referred = self::getReferredData($arrayReferrals[0]);
        JsonResponse::read('referred', $_referred);
    }

    public static function store()
    {
        $referredQuery = new Querys('referrals');

        $arrayReferrals['referrals_id'] = $_GET['id'] . $_GET['id_2'];
        $arrayReferrals['user_id'] = $_GET['id'];
        $arrayReferrals['referred_id'] = $_GET['id_2'];

        date_default_timezone_set('America/Caracas');
        $arrayReferrals['create_date'] = date(self::TIME);

        $referredQuery->insert($arrayReferrals);
        return 'added as an referred';
    }

    public static function destroy()
    {
        $referredQuery = new Querys('referrals');

        $referrals_id = $_GET['id'] . $_GET['id_2'];
        $arrayReferrals = $referredQuery->select('referrals_id', $referrals_id);
        if ($arrayReferrals == false) throw new Exception('not found resourse', 404);

        $referredQuery->delete('referrals_id', $referrals_id);
        JsonResponse::removed();
    }

    protected static function getReferredData($referred)
    {
        $userQuery = new Querys('users');

        $referred_id = $referred->referred_id;
        $arrayUser = $userQuery->select('user_id', $referred_id, 'user_id, email, username, avatar, phone');

        $_referred = $arrayUser[0];
        $_referred->create_date = $referred->create_date;

        return $_referred;
    }
}
