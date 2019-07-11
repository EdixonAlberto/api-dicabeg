<?php

namespace V2\Controllers;

use Exception;
use V2\Modules\Access;
use V2\Database\Querys;
use V2\Middleware\Auth;
use V2\Modules\Security;
use V2\Modules\Diffusion;
use V2\Email\EmailTemplate;
use V2\Modules\JsonResponse;

class AccountController
{
    public static function activation($req) : void
    {
        $body = $req->body;

        if (isset($body->temporal_code)) {
            $id = Querys::table('accounts')->select('user_id')
                ->where('temporal_code', $body->temporal_code)
                ->get(function () {
                    throw new Exception('code invalid or used', 400);
                });

            Querys::table('users')->update(['activated' => true])
                ->where('user_id', $id)
                ->execute();

            Querys::table('accounts')->update(['temporal_code' => 'used'])
                ->where('user_id', $id)
                ->execute();

        } else throw new Exception('temporal_code is not set', 400);

        JsonResponse::OK('activated account');
    }

    public static function login($req) : void
    {
        $body = $req->body;

        if (!isset($body->password))
            throw new Exception('password is not set', 401);

        $userQuery = Querys::table('users')
            ->select(self::USERS_COLUMNS);

        if (isset($body->email)) {
            $user = $userQuery->where('email', $body->email)
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

        if ($user->activated) self::passwordValidate($body, $user);
        else throw new Exception('account not activated', 403);

        JsonResponse::OK('granted access', (object)[
            'access_data' => (new Access(Auth::$id = $user->user_id))->data,
            'user' => $user
        ]);
    }

    public static function loginRefresh() : void
    {
        JsonResponse::OK('renoved access', (object)[
            'access_data' => (new Access(Auth::$id))->data
        ]);
    }

    public static function oauthLogin() : void
    {

    }

    public static function recovery($req) : void
    {
        $body = $req->body;

        if (isset($body->email)) {
            $user = Querys::table('users')
                ->select(['user_id', 'activated', 'email'])
                ->where('email', $body->email)
                ->get(function () {
                    throw new Exception('email not found', 404);
                });

            if ($user->activated) $temporalCode = Security::generateCode(6);
            else throw new Exception('account not activated', 403);

            Querys::table('accounts')->update([
                'temporal_code' => $temporalCode
            ])->where('user_id', $user->user_id)->execute();

            $emailStatus = Diffusion::sendEmail(
                $user->email,
                EmailTemplate::passwordRecovery($temporalCode)
            );

            JsonResponse::OK('activated account', $emailStatus);

        } elseif (isset($body->temporal_code)) {
            if (isset($body->password)) {
                $user_id = Querys::table('accounts')
                    ->select('user_id')
                    ->where('temporal_code', $body->temporal_code)
                    ->get(function () {
                        throw new Exception('code incorrect or used', 400);
                    });

                Querys::table('users')->update([
                    'password' => Security::generateHash($body->password)
                ])->where('user_id', $user_id)
                    ->execute(function () {
                        throw new Exception('error updated password', 500);
                    });

                Querys::table('accounts')->update([
                    'temporal_code' => 'used'
                ])->where('user_id', $user_id)->execute();

            } else throw new Exception('password is not set', 400);

            JsonResponse::OK('recovery successful: password updated');

        } else throw new Exception(
            'email or temporal_code is not set',
            400
        );
    }

    public static function sendEmail($req) : void
    {
        $user = Querys::table('users')
            ->select(['user_id', 'email'])
            ->where('email', $req->body->email)
            ->get(function () {
                throw new Exception('email not found', 404);
            });

        $temporalCode = Querys::table('accounts')
            ->select('temporal_code')
            ->where('user_id', $user->user_id)
            ->get();

        if ($temporalCode == 'used') throw new Exception(
            'temporal_code not generated for this account',
            404
        );

        Querys::table('accounts')->update([
            'temporal_code' => $temporalCode
        ])->where('user_id', $user->user_id)->execute();

        $emailStatus = Diffusion::sendEmail(
            $user->email,
            EmailTemplate::accountActivation($temporalCode)
        );

        JsonResponse::OK('send email', $emailStatus);
    }

    private static function passwordValidate(object $body, object $user) : void
    {
        $verify = password_verify($body->password, $user->password);
        if (!$verify) throw new Exception('passsword incorrect', 401);

        $rehash = Security::rehash($user->password);
        if ($rehash) {
            Querys::table('users')
                ->update(['password' => $user->password])
                ->where('user_id', $user->user_id)
                ->execute();
        }
    }
}
