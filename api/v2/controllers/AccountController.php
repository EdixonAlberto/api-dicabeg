<?php

namespace V2\Controllers;

use Exception;
use V2\Modules\Time;
use V2\Modules\User;
use V2\Modules\Access;
use V2\Modules\Format;
use Modules\Tools\Code;
use V2\Database\Querys;
use V2\Modules\Diffusion;
use Modules\Tools\Password;
use V2\Interfaces\IResource;
use V2\Modules\JsonResponse;
use V2\Modules\EmailTemplate;
use Modules\Exceptions\CodeException;

class AccountController implements IResource
{
    public static function activation($req): void
    {
        $body = $req->body;
        $email = Format::email($body->email ?? '');

        if (isset($body->temporal_code)) {
            $saved_temporal_code = Querys::table('accounts')
                ->select('temporal_code')
                ->where('email', $email)
                ->get(function () {
                    throw new Exception('email not found', 404);
                });

            if (Code::validate($body->temporal_code, $saved_temporal_code)) {
                Querys::table('accounts')->update(['temporal_code' => 'used'])
                    ->where('email', $email)
                    ->execute();

                Querys::table('users')->update(['activated' => true])
                    ->where('email', $email)
                    ->execute();
            }

            $info['email_accountCreated'] = Diffusion::sendEmail(
                $body->send_email,
                $email,
                (new EmailTemplate)->accountCreated()
            );
        } else throw new Exception('temporal_code is not set', 400);

        JsonResponse::OK('activated account', $info);
    }

    public static function login($req): void
    {
        $body = $req->body;

        if (!isset($body->password))
            throw new Exception('password is not set', 401);

        $userQuery = Querys::table('users')
            ->select(self::USERS_COLUMNS);

        if (isset($body->email)) {
            $email = Format::email($body->email ?? '');

            $user = $userQuery->where('email', $email)
                ->get(function () {
                    throw new Exception('email not found', 404);
                });
        } elseif (isset($body->username)) {
            $user = $userQuery->where('username', $body->username)
                ->get(function () {
                    throw new Exception('username not found', 404);
                });
        } else throw new Exception(
            'enter email or username to login',
            400
        );

        if ($user->activated) {
            Password::validate($body->password, $user);
            User::$email = $user->email;
        } else throw new Exception('account not activated', 403);

        JsonResponse::OK('granted access',  [
            'access_data' => (new Access($user->user_id))->data,
            'user' => $user
        ]);
    }

    public static function loginRefresh(): void
    {
        JsonResponse::OK('renoved access', [
            'access_data' => (new Access(User::$id))->data
        ]);
    }

    public static function oauthLogin(): void
    { }

    public static function recovery($req): void
    {
        $body = $req->body;
        $email = Format::email($body->email ?? '');

        if (isset($body->temporal_code)) {
            $newPass = Password::create($body->new_password ?? $body->password ?? '');

            $saved_temporal_code = Querys::table('accounts')
                ->select('temporal_code')
                ->where('email', $email)
                ->get(function () {
                    throw new Exception('email not found', 404);
                });

            if (Code::validate($body->temporal_code, $saved_temporal_code)) {
                $timeZone = Querys::table('accounts')
                    ->select('time_zone')
                    ->where('email', $email)
                    ->get();

                Querys::table('users')->update([
                    'password' => $newPass,
                    'update_date' => Time::current($timeZone)->utc
                ])->where('email', $email)
                    ->execute(function () {
                        throw new Exception('error updated password', 500);
                    });

                Querys::table('accounts')->update(['temporal_code' => 'used'])
                    ->where('email', $email)
                    ->execute();
            }

            $info['email_successfull'] = Diffusion::sendEmail(
                $body->send_email,
                $email,
                (new EmailTemplate)->successfull([
                    'updatedData' => 'cuenta Dicabeg'
                ])
            );

            JsonResponse::OK('recovery successful, password updated', $info);
        } else {
            $activatedAccount = Querys::table('users')
                ->select('activated')
                ->where('email', $email)
                ->get(function () {
                    throw new Exception('email not found', 404);
                });

            if ($activatedAccount) {
                $code = Code::create();

                $timeZone = Querys::table('accounts')->select('time_zone')
                    ->where('email', $email)
                    ->get();

                Querys::table('accounts')->update([
                    'last_email_sended' => 'accountRecovery',
                    'temporal_code' => $code,
                    'code_create_date' => Time::current($timeZone)->utc
                ])->where('email', $email)->execute();
            } else throw new Exception('account not activated', 403);

            $info['email_accountRecovery'] = Diffusion::sendEmail(
                $body->send_email,
                $email,
                (new EmailTemplate)->accountRecovery(['code' => $code])
            );

            JsonResponse::OK('email sended', $info);
        }
    }

    public static function resendEmail($req): void
    {
        $body = $req->body;
        $email = Format::email($body->email ?? '');

        $account = Querys::table('accounts')
            ->select(['temporal_code', 'last_email_sended', 'time_zone'])
            ->where('email', $email)
            ->get(function () {
                throw new Exception('email not found', 404);
            });

        if ($account->temporal_code != 'used') {
            $code = Code::create();
            $timeZone = $account->time_zone;
            $emailType = $account->last_email_sended;

            Querys::table('accounts')->update([
                'temporal_code' => $code,
                'code_create_date' => Time::current($timeZone)->utc
            ])->where('email', $email)->execute();
        } else throw new CodeException('used');

        $info['email_' . $emailType] = Diffusion::sendEmail(
            $body->send_email,
            $email,
            (new EmailTemplate)->$emailType(['code' => $code])
        );

        JsonResponse::OK('email resended', $info);
    }
}
