<?php

namespace V2\Controllers;

use Exception;
use V2\Modules\Time;
use V2\Modules\User;
use V2\Modules\Format;
use Modules\Tools\Code;
use V2\Database\Querys;
use V2\Modules\Diffusion;
use V2\Interfaces\IResource;
use V2\Modules\JsonResponse;
use V2\Modules\EmailTemplate;

class TransferController implements IResource
{
    private const MIN_AMOUNT = 0.0001;
    private const MAX_AMOUNT = 10000;
    private const RESPONSIBLE_DATA = ['username', 'email', 'avatar'];
    private const RECEPTOR_DATA = [
        'user_id', 'activated', 'email', 'balance', 'player_id'
    ];

    public static function index($req): void
    {
        $arrayTransfers = Querys::table('transfers')
            ->select(self::TRANSFERS_COLUMNS)
            ->where('user_id', User::$id)
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
            ->where([
                'user_id' => User::$id,
                'transfer_code' => $req->params->code,
            ])->get(function () {
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
        $userQuery = Querys::table('users');

        // Condiciones para efectuar la transferencia
        if (User::$balance < $amount) throw new Exception(
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
            $receptorQuery = $userQuery->select(self::RECEPTOR_DATA);

            if ($body->type_receptor == 'username') {
                if ($body->receptor == User::$username)
                    throw new Exception('receptor incorrect', 400);
                $receptorQuery->where('username', $body->receptor);
            } elseif ($body->type_receptor == 'email') {
                if ($body->receptor == User::$email)
                    throw new Exception('receptor incorrect', 400);
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

        // Ejecutar la transferencia
        $commission = self::processCommission($amount, $receptor->email);
        $transferAmount = $amount - $commission;
        $transfer_code = Code::create();
        $newUserBalance = User::$balance - $amount;
        $newReceptorBalance = $receptor->balance + $transferAmount;
        $currentTime = Time::current()->utc;
        $info = null;

        $userQuery->update(['balance' => $newUserBalance])
            ->where('user_id', User::$id)
            ->execute();

        $userQuery->update(['balance' => $newReceptorBalance])
            ->where('user_id', $receptor->user_id)
            ->execute();

        Querys::table('transfers')->insert($transfer = (object) [
            'user_id' => User::$id,
            'transfer_code' => $transfer_code,
            'concept' => $body->concept ?? null,
            'responsible' => $receptor->user_id,
            'amount' => -$amount,
            'previous_balance' => User::$balance,
            'current_balance' => $newUserBalance,
            'create_date' => $currentTime
        ])->execute();

        Querys::table('transfers')->insert([
            'user_id' => $receptor->user_id,
            'transfer_code' => $transfer_code,
            'concept' => $body->concept ?? null,
            'responsible' => User::$id,
            'amount' => $transferAmount,
            'previous_balance' => $receptor->balance,
            'current_balance' => $newReceptorBalance,
            'create_date' => $currentTime
        ])->execute();

        $userCommission = Querys::table('commissions')
            ->select(['amount', 'commission'])
            ->where('user_id', User::$id)
            ->get();

        if ($userCommission === false) {
            Querys::table('commissions')->insert([
                "user_id" => User::$id,
                "amount" => $amount,
                "commission" => $commission,
                "create_date" => $currentTime,
            ])->execute();
        } else {
            Querys::table('commissions')->update([
                "amount" =>  $userCommission->amount + $amount,
                "commission" => $userCommission->commission + $commission,
                "create_date" => $currentTime,
            ])->where('user_id', User::$id)->execute();
        }

        // TODO: Apagando la funsion de notificaciones. REPARAR esto despues

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

        $path = SERVER_URL . "/transfers/{$transfer->transfer_code}";

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

    private static function processCommission(float $amount, string $email): float
    {
        $options = Querys::table('options')
            ->select(['commission_amount', 'commission_percentage'])
            ->get();

        if ($amount >= $options->commission_amount) {
            $arrayEnterprises = Querys::table('view_enterprises')
                ->select('email')
                ->getAll();

            // Se verifica que el email del receptor no sea de una empresa
            if ($arrayEnterprises) {
                foreach ($arrayEnterprises as $enterprise) {
                    if ($email == $enterprise->email) return 0;
                }
            }

            // Se aplica la comision segun el monto
            return $amount * ($options->commission_percentage / 100);
        } else return 0;
    }
}
