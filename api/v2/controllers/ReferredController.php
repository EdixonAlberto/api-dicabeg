<?php

namespace V2\Controllers;

use Exception;
use V2\Modules\Time;
use V2\Database\Querys;
use V2\Modules\JsonResponse;
use V2\Interfaces\IController;

class ReferredController implements IController
{
    public static function index() : void
    {
        $arrayReferred_id = Querys::table('referrals')
            ->select('referred_id')
            ->where('user_id', USERS_ID)
            ->group(GROUP_NRO)
            ->getAll(function () {
                throw new Exception('referrals not found', 404);
            });

        foreach ($arrayReferred_id as $referred) {
            $arrayReferrals[] = Querys::table('users')
                ->select(['email', 'username', 'avatar', 'phone'])
                ->where('user_id', $referred->referred_id)->get();
        }

        JsonResponse::read($arrayReferrals);
    }

    public static function show() : void
    {
        Querys::table('referrals')
            ->select('referred_id')
            ->where([
                'user_id' => USERS_ID,
                'referred_id' => REFERRALS_ID
            ])->get(function () {
                throw new Exception('referred not found', 404);
            });

        $referred = Querys::table('users')
            ->select(['email', 'username', 'avatar', 'phone'])
            ->where('user_id', REFERRALS_ID)->get();

        JsonResponse::read((array)$referred);
    }

    public static function store($body) : void
    {
        Querys::table('referrals')->insert([
            'user_id' => $body->user_id,
            'referred_id' => $body->referred_id,
            'create_date' => Time::current($body->time_zone)->utc
        ])->execute();
    }

    public static function update($body) : void
    {
    }

    public static function destroy() : void
    {
        Querys::table('referrals')->delete()
            ->where([
                'user_id' => USERS_ID,
                'referred_id' => REFERRALS_ID
            ])->execute(function () {
                throw new Exception('referred not found', 404);
            });

        Querys::table('accounts')->update(['registration_code' => 'deleted'])
            ->where('user_id', REFERRALS_ID)
            ->execute();
    }
}
