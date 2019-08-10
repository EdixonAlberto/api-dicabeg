<?php

namespace V2\Controllers;

use Exception;
use V2\Modules\Time;
use V2\Modules\User;
use V2\Modules\Format;
use Modules\Tools\Code;
use V2\Database\Querys;
use V2\Middleware\Auth;
use V2\Modules\Diffusion;
use V2\Interfaces\IResource;
use V2\Modules\JsonResponse;
use V2\Modules\EmailTemplate;

class TransferController implements IResource
{
    private const COMMISSION = 5 / 100; // 5% de comisión
    private const MIN_AMOUNT = 0.0001;
    private const MAX_AMOUNT = 1;
    private const RESPONSIBLE_DATA = ['username', 'email', 'avatar'];

    public static function index($req): void
    {
        $arrayTransfers = Querys::table('transfers')
            ->select(self::TRANSFERS_COLUMNS)
            ->where('user_id', Auth::$id)
            ->group($req->params->nro, $req->params->order)
            ->getAll(function () {
                throw new Exception('transfers not exist', 404);
            });

        foreach ($arrayTransfers as $transfer) {
            $transfer->responsible = Querys::table('users')
                ->select(self::RESPONSIBLE_DATA)
                ->where('user_id', $transfer->responsible)
                ->get();

            $_arrayTransfers[] = $transfer;
        }

        JsonResponse::read($_arrayTransfers);
    }

    public static function show($req): void
    {
        $transfer = Querys::table('transfers')
            ->select(self::TRANSFERS_COLUMNS)
            ->where('transfer_code', $req->params->code)
            ->get(function () {
                throw new Exception('transfer not found', 404);
            });

        $transfer->responsible = Querys::table('users')
            ->select(self::RESPONSIBLE_DATA)
            ->where('user_id', $transfer->responsible)
            ->get();

        JsonResponse::read($transfer);
    }

    public static function store($req): void
    {
        $body = $req->body;

        $amount = Format::number($body->amount);

        // TODO: usar una constante para esto
        $commission = ($amount >= 0.005) ? $amount * self::COMMISSION : 0;

        $transferAmount = $amount - $commission;

        $userQuery = Querys::table('users');

        $user = $userQuery->select(['username', 'balance', 'player_id'])
            ->where('user_id', Auth::$id)
            ->get();

        if ($user->balance < $amount) throw new Exception(
            'insufficient balance',
            400
        );
        elseif ($amount < self::MIN_AMOUNT) throw new Exception(
            'amount must be greater than equal to: ' . self::MIN_AMOUNT,
            400
        );
        elseif ($amount > self::MAX_AMOUNT) throw new Exception(
            'amount must be less than or equal to: ' . self::MAX_AMOUNT,
            400
        );

        if (isset($body->type_receptor)) {
            $receptorQuery = $userQuery
                ->select(['user_id', 'activated', 'balance', 'player_id']);

            if ($body->type_receptor == 'username') {
                $receptorQuery->where('username', $body->receptor);
            } elseif ($body->type_receptor == 'email') {
                $receptorQuery->where('email', $body->receptor);
            } else throw new Exception(
                "type_receptor field should be: 'username' or 'email'",
                400
            );

            $receptor = $receptorQuery->get(function () {
                throw new Exception('receptor not found', 404);
            });
        } else throw new Exception('type_receptor is not set', 400);

        if ($receptor->activated == false)
            throw new Exception("account not activated: {$body->receptor}", 400);

        $transfer_code = Code::create();
        $userBalance = $user->balance - $amount;
        $receptorBalance = $receptor->balance + $transferAmount;
        $currentTime = Time::current()->utc;
        $info = null;

        $userQuery->update(['balance' => $userBalance])
            ->where('user_id', Auth::$id)
            ->execute();

        $userQuery->update(['balance' => $receptorBalance])
            ->where('user_id', $receptor->user_id)
            ->execute();

        Querys::table('transfers')->insert($transfer = (object) [
            'user_id' => Auth::$id,
            'transfer_code' => $transfer_code,
            'concept' => $body->concept ?? null,
            'responsible' => $receptor->user_id,
            'amount' => -$amount,
            'previous_balance' => $user->balance,
            'current_balance' => $userBalance,
            'create_date' => $currentTime
        ])->execute();

        Querys::table('transfers')->insert([
            'user_id' => $receptor->user_id,
            'transfer_code' => $transfer_code,
            'concept' => $body->concept ?? null,
            'responsible' => Auth::$id,
            'amount' => +$transferAmount,
            'previous_balance' => $receptor->balance,
            'current_balance' => $receptorBalance,
            'create_date' => $currentTime
        ])->execute();

        $userCommission = Querys::table('commissions')
            ->select(['amount', 'commission'])
            ->where('user_id', Auth::$id)
            ->get();

        if ($userCommission === false) {
            Querys::table('commissions')->insert([
                "user_id" => Auth::$id,
                "amount" => $amount,
                "commission" => $commission,
                "create_date" => $currentTime,
            ])->execute();
        } else {
            Querys::table('commissions')->update([
                "amount" =>  $userCommission->amount + $amount,
                "commission" => $userCommission->commission + $commission,
                "create_date" => $currentTime,
            ])->where('user_id', Auth::$id)->execute();
        }

        // TODO: Apando la funsion de notificaciones. REPARAR esto despues

        // if (isset($user->player_id) and $user->player_id != '') {
        //     $info['notifications']['user'] =
        //         Diffusion::sendNotification(
        //         [$user->player_id],
        //         'Transferencia realizada exitosamente, ' .
        //             'por un monto de: ' . $amount
        //     );
        // }

        // if (isset($receptor->player_id) and $receptor->player_id != '') {
        //     $info['notifications']['receptor'] =
        //         Diffusion::sendNotification(
        //         [$receptor->player_id],
        //         "El usuario: {$user->username}" .
        //             'te ha realizado una transferencia, ' .
        //             'por un monto de:' . $transferAmount
        //     );
        // }

        $path = "https://{$_SERVER['SERVER_NAME']}/api/transfers/{$transfer->transfer_code}";

        JsonResponse::created($transfer, $path, $info);
    }

    public static function sendReport($req): void
    {
        $body = $req->body;

        $report = Querys::table('commissions')
            ->select(self::COMMISSIONS_COLUMNS)
            ->where('user_id', User::$id)
            ->get();

        if ($report) {
            $template = new EmailTemplate;
            $info['email_report'] = Diffusion::sendEmail(
                $body->send_email,
                $template::SUPPORT_EMAIL,
                $template->report($report = [
                    'id' => $report->user_id,
                    'amount' => $report->amount,
                    'commission' => $report->commission,
                    'dateLast' => $report->create_date
                ])
            );
            $info['report'] = $report;
        } else throw new Exception(
            'report not generated: transfers not exist',
            400
        );

        JsonResponse::OK('report sended', $info);
    }

    // TODO: metodo pendiente
    public static function destroy($req): void
    { }
}
