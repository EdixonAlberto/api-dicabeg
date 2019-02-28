<?php

namespace V1\Users\Referrals;

use Db\Querys;
use Exception;
use Tools\JsonResponse;
use V1\Referrals\ReferralsQuerys;
use V1\Users\UsersQuerys;

require_once 'ReferralsQuerys.php';

class Referrals
{
    public static function getReferralsAlls()
    {
        $arrayReferrals = ReferralsQuerys::selectAlls();
        for ($i = 0; $i < count($arrayReferrals); $i++) {
            $referred = $arrayReferrals[$i];
            $referredData = self::getReferredData($referred);
            $arrayReferralsData[] = $referredData;
        }
        JsonResponse::read('referrals', $arrayReferralsData);
    }

    public static function getReferredById()
    {
        $arrayReferrals = ReferralsQuerys::selectAlls();
        $referred = self::extractReferred($arrayReferrals);
        $referredData = self::getReferredData($referred);
        JsonResponse::read('referred', $referredData);
    }

    public static function createReferred($referred_id)
    {
        $referredQuery = new Querys('referrals');

        $arrayReferrals = $referredQuery->select('user_id', $_GET['id'], 'referrals_data');
        $arrayReferrals = $arrayReferrals[0]->referrals_data;
        $arrayReferrals = json_decode($arrayReferrals);

        $arrayReferrals[] = (object)[
            'referred_id' => $referred_id,
            'create_date' => date('Y-m-d H:i')
        ];

        var_dump($arrayReferrals);

        $_arrayReferrals['referrals_data'] = json_encode($arrayReferrals);

        var_dump($_arrayReferrals);
        die;

        $referredQuery->update('user_id', $_GET['id'], $_arrayReferrals);

        return 'added as an referred';
    }

    public static function removeReferred($user_id = null)
    {
        $referredQuery = new Querys('referrals');

        $arrayReferrals = $referredQuery->select('user_id', $user_id, 'referrals_data');
        $arrayReferrals = $arrayReferrals[0]->referrals_data;
        $arrayReferrals = json_decode($arrayReferrals);


        self::extractReferred($arrayReferrals, $i);
        unset($arrayReferrals[$i]);


        var_dump($arrayReferrals, json_encode($arrayReferrals));
        die;

        $_arrayReferrals['referrals_data'] = json_encode($arrayReferrals);


        $referredQuery->update('user_id', $user_id, $_arrayReferrals);

        if (is_null($user_id)) JsonResponse::removed();
    }

    protected static function extractReferred($arrayReferrals, &$index = null)
    {
        for ($i = 0; $i < count($arrayReferrals); $i++) {
            $referred = $arrayReferrals[$i];
            if ($referred->referred_id === $_GET['id']) {
                $index = $i;
                return $referred;
            } else continue;
        }
        throw new Exception('not found resourse', 404);
    }

    protected static function getReferredData($referred)
    {
        $_GET['id'] = $referred->referred_id;
        $user = UsersQuerys::selectById('user_id, email, username, avatar, phone');
        $user->create_date = $referred->create_date;
        return $user;
    }
}
