<?php

namespace V1\Users\Referrals;

use Db\Querys;
use Tools\Constants;
use Tools\JsonResponse;
use V1\Options\Time;

class Referrals extends Constants
{
    public static function index()
    {
        $referredQuery = new Querys('referrals');

        $arrayReferrals = $referredQuery->select('user_id', $_GET['id'], self::SET_REFERRALS, true);
        if ($arrayReferrals == false) throw new \Exception('not found resourse', 404);

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
        $referred = $referredQuery->select('referrals_id', $referrals_id, self::SET_REFERRALS);
        if ($referred == false) throw new \Exception('not found resourse', 404);

        $_referred = self::getReferredData($referred);
        JsonResponse::read('referred', $_referred);
    }

    public static function store()
    {
        $referredQuery = new Querys('referrals');

        $arrayReferrals['referrals_id'] = $_GET['id'] . $_GET['id_2'];
        $arrayReferrals['user_id'] = $_GET['id'];
        $arrayReferrals['referred_id'] = $_GET['id_2'];
        $arrayReferrals['create_date'] = Time::current('UTC');

        $referredQuery->insert($arrayReferrals);
        return 'added as an referred';
    }

    public static function destroy()
    {
        $referredQuery = new Querys('referrals');

        $referrals_id = $_GET['id'] . $_GET['id_2'];
        $referred = $referredQuery->select('referrals_id', $referrals_id);
        if ($referred == false) throw new \Exception('not found resourse', 404);

        $referredQuery->delete('referrals_id', $referrals_id);
        JsonResponse::removed();
    }

    protected static function getReferredData($referred)
    {
        $userQuery = new Querys('users');

        $user = $userQuery->select('user_id', $referred->referred_id, 'user_id, email, username, avatar, phone');
        $user->create_date = $referred->create_date;
        return $user;
    }
}
