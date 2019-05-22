<?php

namespace V2\Controllers;

use Exception;
use V2\Modules\Time;
use V2\Modules\Format;
use V2\Database\Querys;
use V2\Modules\Security;
use V2\Modules\Diffusion;
use V2\Modules\JsonResponse;
use V2\Interfaces\IController;

class TransferController implements IController
{
    public static function info() : void
    {
        $routeServer = 'https://' . $_SERVER['SERVER_NAME'];
        JsonResponse::OK(
            [
                'routes' => [
                    "GET: {$routeServer}/transfers/group/nro",
                    "GET: {$routeServer}/transfers/id",
                    "POST: {$routeServer}/transfers"
                ]
            ]
        );
    }

    public static function index() : void
    {
        $arrayTransfers = Querys::table('transfers')
            ->select(['transfer_nro, username, amount, total, create_date'])
            ->where('user_id', USERS_ID)
            ->group(GROUP_NRO)
            ->getAll(function () {
                throw new Exception('transfers not found', 404);
            });

        JsonResponse::read($arrayTransfers);
    }

    public static function show() : void
    {
        $transfer = Querys::table('transfers')
            ->select(['transfer_nro, username, amount, total, create_date'])
            ->where('transfer_nro', TRANSFERS_ID)
            ->get(function () {
                throw new Exception('transfer not found', 404);
            });

        JsonResponse::read($transfer);
    }

    public static function store($body) : void
    {
        $amount = Format::number($body->amount);
        $userQuery = Querys::table('users');

        $user = $userQuery
            ->select(['username', 'money'])
            ->where('user_id', USERS_ID)
            ->get();

        if ($user->money < $amount)
            throw new Exception('insufficient money', 400);

        $receptor = $userQuery
            ->select(['player_id', 'money'])
            ->where('username', $body->username)
            ->get(function () {
                throw new Exception('username not found', 404);
            });

        $userQuery->update($total = (object)[
            'money' => $user->money - $amount
        ])->where('user_id', USERS_ID)
            ->execute();

        $userQuery->update([
            'money' => $receptor->money + $amount
        ])->where('username', $body->username)
            ->execute();

        Querys::table('transfers')->insert($transfer = (object)[
            'transfer_nro' => Security::generateCode(14),
            'user_id' => USERS_ID,
            'username' => $body->username,
            'amount' => $amount,
            'total' => $total->money,
            'create_date' => Time::current()->utc
        ])->execute();

        if (!is_null($receptor->player_id)) {
            $info['notifications'] = Diffusion::sendNotification(
                $receptor->player_id,
                "El usuario: {$user->username} te ha realizado una transferencia"
            );
        } else $info = null;

        $path = 'https://' . $_SERVER['SERVER_NAME'] .
            '/v2/transfers/' . $transfer->transfer_nro;

        JsonResponse::created($transfer, $path, $info);
    }

    public static function update($body) : void
    {
    }

    public static function destroy() : void
    {
    }
}
