<?php

namespace V2\Controllers;

use V2\Modules\User;
use V2\Database\Querys;
use V2\Modules\JsonResponse;

class AppController
{
    private const USER_DATA = ['username', 'email', 'balance'];

    public static function getRoles(): void
    {
        $arrayRoles = Querys::table('roles')
            ->select(['rol_id', 'name'])
            ->getAll(function () {
                throw new Exception('resource not found', 404);
            });

        foreach ($arrayRoles as $rol) {
            $_arrayRoles[$rol->rol_id] = $rol->name;
        }

        JsonResponse::OK('list of roles', $_arrayRoles);
    }

    public static function getRanking(): void
    {
        $arrayRanking = Querys::table('view_ranking')
            ->select(self::USER_DATA)
            ->getAll(function () {
                new \Modules\Exceptions\ResourceException;
            });

        $top10 = [0 => null];

        foreach ($arrayRanking as $key => $user) {
            // Obteniendo el TOP 10
            if ($key < 10) {
                $top10[] = $user;

                // Obteniendo la posicion del usuario en el ranking
                if (isset($userPosition) == false) {
                    if (User::$email == $user->email)
                        $userPosition = ++$key;
                }
            }
        }

        $info['user_position'] = $userPosition ?? null;

        JsonResponse::read($top10, $info);
    }

    public static function totalBalance()
    {
        $info['balance'] = Querys::table('view_total_balance')
            ->select('balance')
            ->get(function () {
                new \Modules\Exceptions\ResourceException;
            });

        JsonResponse::OK('total balances generated', $info);
    }

    public static function commissions()
    {
        $arrayCommissions = Querys::table('commissions')
            ->select('*')
            ->getAll(function () {
                new \Modules\Exceptions\ResourceException;
            });

        JsonResponse::OK('commissions', $arrayCommissions);
    }
}
