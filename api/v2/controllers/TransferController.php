<?php

namespace V2\Controllers;

use Exception;
use V2\Modules\Time;
use V2\Modules\Format;
use V2\Database\Querys;
use V2\Modules\Security;
use V2\Modules\Diffusion;
use V2\Modules\Middleware;
use V2\Modules\JsonResponse;
use V2\Interfaces\IController;

class TransferController implements IController
{
    private const COMMISSION = 5 / 100; // 5% de comisión

    public static function info() : void
    {
        JsonResponse::OK([
            'show' => [
                'GET1' => "/v2/transfers/group/nro",
                'GET2' => "/v2/transfers/id",
            ],
            'create' => [
                'POST' => "/v2/transfers",
                'body' => [
                    'concept',
                    'username',
                    'amount'
                ]
            ]
        ]);
    }

    public static function index() : void
    {
        $arrayTransfers = Querys::table('transfers')
            ->select(self::TRANSFERS_COLUMNS)
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
            ->select(self::TRANSFERS_COLUMNS)
            ->where('transfer_nro', TRANSFERS_ID)
            ->get(function () {
                throw new Exception('transfer not found', 404);
            });

        JsonResponse::read($transfer);
    }

    public static function store($body) : void
    {
        $amount = Format::number($body->amount);
        $commission = $amount * self::COMMISSION;
        $transferAmount = $amount - $commission;

        $userQuery = Querys::table('users');

        $user = $userQuery->select(['username', 'balance'])
            ->where('user_id', USERS_ID)->get();
        if ($user->balance < $amount)
            throw new Exception('insufficient balance', 400);

        $receptor = $userQuery
            ->select(['user_id', 'player_id', 'balance'])
            ->where('username', $body->username)
            ->get(function () {
                throw new Exception('username not found', 404);
            });

        Middleware::activation($receptor->user_id);

        $transfer_nro = Security::generateCode(6);
        $userBalance = $user->balance - $amount;
        $receptorBalance = $receptor->balance + $transferAmount;
        $currentTime = Time::current()->utc;

        $userQuery->update(['balance' => $userBalance])
            ->where('user_id', USERS_ID)
            ->execute();

        $userQuery->update(['balance' => $receptorBalance])
            ->where('user_id', $receptor->user_id)
            ->execute();

        Querys::table('transfers')->insert($transfer = (object)[
            'user_id' => USERS_ID,
            'transfer_nro' => $transfer_nro,
            'concept' => $body->concept ?? null,
            'username' => $body->username,
            'amount' => -$amount,
            'previous_balance' => $user->balance,
            'current_balance' => $userBalance,
            'create_date' => $currentTime
        ])->execute();

        Querys::table('transfers')->insert([
            'user_id' => $receptor->user_id,
            'transfer_nro' => $transfer_nro,
            'concept' => $body->concept ?? null,
            'username' => $user->username,
            'amount' => +$transferAmount,
            'previous_balance' => $receptor->balance,
            'current_balance' => $receptorBalance,
            'create_date' => $currentTime
        ])->execute();

        // TODO: mas adelante las comisiones pueden ser dinamicas
        // en ese caso el campo: commission será muy util
        Querys::table('commissions')->insert([
            "transfer_nro" => $transfer_nro,
            "amount" => -$amount,
            "commission" => self::COMMISSION * 100, // expresado en %
            "gain" => $commission,
            "create_date" => $currentTime,
        ])->execute();

        if (!is_null($receptor->player_id)) {
            $info['notifications'] = Diffusion::sendNotification(
                $receptor->player_id,
                "El usuario: {$user->username}" .
                    'te ha realizado una transferencia'
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
