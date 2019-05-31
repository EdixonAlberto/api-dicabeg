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
            'GET' => [
                'route_1' => '/v2/transfers/group/nro',
                'route_2' => '/v2/transfers/id'
            ],
            'POST' => [
                'route_1' => '/v2/transfers',
                'body' => [
                    'concept?',
                    'username!',
                    'amount!'
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
            ->where('transfer_code', TRANSFERS_ID)
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

        $user = $userQuery->select(['player_id', 'username', 'balance'])
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

        $transfer_code = Security::generateCode(6);
        $userBalance = $user->balance - $amount;
        $receptorBalance = $receptor->balance + $transferAmount;
        $currentTime = Time::current()->utc;
        $info = null;

        $userQuery->update(['balance' => $userBalance])
            ->where('user_id', USERS_ID)
            ->execute();

        $userQuery->update(['balance' => $receptorBalance])
            ->where('user_id', $receptor->user_id)
            ->execute();

        Querys::table('transfers')->insert($transfer = (object)[
            'user_id' => USERS_ID,
            'transfer_code' => $transfer_code,
            'concept' => $body->concept ?? null,
            'username' => $body->username,
            'amount' => -$amount,
            'previous_balance' => $user->balance,
            'current_balance' => $userBalance,
            'create_date' => $currentTime
        ])->execute();

        Querys::table('transfers')->insert([
            'user_id' => $receptor->user_id,
            'transfer_code' => $transfer_code,
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
            "transfer_code" => $transfer_code,
            "amount" => $amount,
            "commission" => self::COMMISSION * 100, // expresado en %
            "gain" => $commission,
            "create_date" => $currentTime,
        ])->execute();

        if (isset($user->player_id) and $user->player_id != '') {
            $info['notifications']['user'] =
                Diffusion::sendNotification(
                [$user->player_id],
                'Transferencia realizada exitosamente, ' .
                    'por un monto de: ' . $amount
            );
        }

        if (isset($receptor->player_id) and $receptor->player_id != '') {
            $info['notifications']['receptor'] =
                Diffusion::sendNotification(
                [$receptor->player_id],
                "El usuario: {$user->username}" .
                    'te ha realizado una transferencia, ' .
                    'por un monto de:' . $transferAmount
            );
        }

        $path = 'https://' . $_SERVER['SERVER_NAME'] .
            '/v2/transfers/' . $transfer->transfer_code;

        JsonResponse::created($transfer, $path, $info);
    }

    public static function update($body) : void
    {
    }

    public static function destroy() : void
    {
    }
}
