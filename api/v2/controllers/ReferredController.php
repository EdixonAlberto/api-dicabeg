<?php

namespace V2\Controllers;

use Exception;
use V2\Modules\Time;
use V2\Modules\User;
use V2\Database\Querys;
use V2\Modules\JsonResponse;
use V2\Interfaces\IController;

class ReferredController implements IController
{
    private const REFERRED_DATA = [
        'user_id, username', 'email', 'avatar', 'phone'
    ];

    public static function index($req): void
    {
        $arrayReferred = Querys::table('referreds')
            ->select('referred_id')
            ->where('user_id', User::$id)
            ->group($req->params->nro, $req->params->order)
            ->getAll(function () {
                throw new Exception('referreds not exist', 404);
            });

        foreach ($arrayReferred as $referred) {
            $_arrayReferrals[] = Querys::table('users')
                ->select(self::REFERRED_DATA)
                ->where('user_id', $referred->referred_id)->get();
        }

        JsonResponse::read($_arrayReferrals);
    }

    public static function show($req): void
    {
        Querys::table('referreds')
            ->select('referred_id')
            ->where([
                'user_id' => User::$id,
                'referred_id' => $req->params->id
            ])->get(function () {
                throw new Exception('referred not found', 404);
            });

        $referred = Querys::table('users')
            ->select(self::REFERRED_DATA)
            ->where('user_id', $req->params->id)->get();

        JsonResponse::read($referred);
    }

    public static function store($body): void
    {
        Querys::table('referreds')->insert([
            'user_id' => $body->user_id,
            'referred_id' => $body->referred_id,
            'create_date' => Time::current()->utc
        ])->execute();
    }

    public static function update($req): void
    { }

    public static function destroy($req): void
    {
        Querys::table('referreds')->delete()
            ->where(['referred_id' => $req->params->id])
            ->execute(function () {
                throw new Exception('referred not found', 404);
            });

        // TODO: se debe pasar por query-param el email del referred
        // Querys::table('accounts')->update(['referred_id' => ''])
        //     ->where('email', $req->params->email)
        //     ->execute();

        JsonResponse::removed();
    }
}
