<?php

namespace V2\Controllers;

use Exception;
use V2\Libraries\Jwt;
use V2\Database\Querys;
use V2\Modules\Security;
use V2\Modules\Diffusion;
use V2\Modules\Middleware;
use V2\Email\EmailTemplate;
use V2\Modules\JsonResponse;

class AccountController
{
    public static function activateAccount($body)
    {
        if (isset($body->temporal_code)) {
            Querys::table('accounts')->update([
                'activated_account' => 'true',
                'temporal_code' => ''
            ])->where('temporal_code', $body->temporal_code)
                ->execute(function () {
                    throw new Exception('code invalid or used', 400);
                });

        } else throw new Exception('code is not set', 400);

        JsonResponse::OK('activated account');
    }

    public static function userLogin($body)
    {
        $userQuery = Querys::table('users')
            ->select(['user_id', 'password']);

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
            'enter email or username to authenticate',
            400
        );

        Middleware::activation($user->user_id);

        self::passwordValidate($body, $user);

        $token = Jwt::create($user->user_id);

        $path = 'https://' . $_SERVER['SERVER_NAME'] . '/v2/users';
        JsonResponse::created('api_token', $token, $path);
    }

    public static function passwordRecovery($body)
    {
        if (isset($body->email)) {
            $user_id = Querys::table('users')
                ->select('user_id')
                ->where('email', $body->email)
                ->get(function () {
                    throw new Exception('email not found', 404);
                });

            Middleware::activation($user_id);

            $code = Security::generateCode(6);

            Querys::table('accounts')->update([
                'temporal_code' => $code
            ])->where('user_id', $user_id)->execute();

            $emailStatus = Diffusion::sendEmail(
                $body->email,
                EmailTemplate::passwordRecovery($code, 'spanish')
            );

            JsonResponse::OK($emailStatus);

        } elseif (isset($body->temporal_code)) {
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
                'temporal_code' => ''
            ])->where('user_id', $user_id)->execute();

            JsonResponse::OK('recovery successful, password updated');
        }
    }

    private static function passwordValidate($body, $user)
    {
        if (!isset($body->password))
            throw new Exception('passsword is not set', 401);

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
