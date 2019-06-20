<?php

namespace V2\Controllers;

use Exception;
use V2\Modules\Time;
use V2\Libraries\Jwt;
use V2\Database\Querys;
use V2\Modules\Security;
use V2\Modules\Diffusion;
use V2\Modules\Middleware;
use V2\Email\EmailTemplate;
use V2\Interfaces\IResource;
use V2\Modules\JsonResponse;

class AccountController implements IResource
{
    public static function show()
    {
        $invite_code['invite_code'] = Querys::table('accounts')
            ->select('invite_code')
            ->where('user_id', USERS_ID)
            ->get(function () {
                throw new Exception('error, invite_code not generated', 500);
            });

        JsonResponse::read($invite_code);
    }

    public static function activateAccount($body)
    {
        if (isset($body->temporal_code)) {
            Querys::table('accounts')->update([
                'activated_account' => 'true',
                'temporal_code' => 'used'
            ])->where('temporal_code', $body->temporal_code)
                ->execute(function () {
                    throw new Exception('code invalid or used', 400);
                });

        } else throw new Exception('temporal_code is not set', 400);

        JsonResponse::OK('activated account');
    }

    public static function userLogin($body)
    {
        if (!isset($body->password))
            throw new Exception('passsword is not set', 401);

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

        Middleware::activation($user->user_id);

        self::passwordValidate($body, $user);

        define('USERS_ID', $user->user_id);

        JsonResponse::created(
            self::getAccess(USERS_ID),
            'https://' . $_SERVER['SERVER_NAME'] . '/v2/users',
            $user
        );
    }

    public static function oauthLogin() : void
    {

    }

    public static function refreshLogin() : void
    {
        JsonResponse::OK(self::getAccess(USERS_ID));
    }

    public static function accountRecovery($body)
    {
        if (isset($body->email)) {
            $user = Querys::table('users')
                ->select(['user_id', 'email'])
                ->where('email', $body->email)
                ->get(function () {
                    throw new Exception('email not found', 404);
                });

            Middleware::activation($user->user_id);

            $emailStatus = self::sendCode(
                'passwordRecovery',
                Security::generateCode(6),
                $user
            );

            JsonResponse::OK($emailStatus);

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

            } else throw new Exception('attribute {password} not set', 400);

            JsonResponse::OK('recovery successful, password updated');

        } else throw new Exception(
            'enter one of the following parameters: ' .
                '{email, temporal_code, password}',
            400
        );
    }

    public static function sendEmail($body) : void
    {
        $user = Querys::table('users')
            ->select(['user_id', 'email'])
            ->where('email', $body->email)
            ->get(function () {
                throw new Exception('email not found', 404);
            });

        $temporal_code = Querys::table('accounts')
            ->select('temporal_code')
            ->where('user_id', $user->user_id)
            ->get();

        if ($temporal_code == 'used') throw new Exception(
            'temporal_code not generated for this account',
            404
        );

        $emailStatus = self::sendCode(
            'accountActivation',
            $temporal_code,
            $user
        );

        JsonResponse::OK($emailStatus);
    }

    private static function sendCode(string $emailType, string $code, object $for) : object
    {
        Querys::table('accounts')->update([
            'temporal_code' => $code
        ])->where('user_id', $for->user_id)->execute();

        return Diffusion::sendEmail(
            $for->email,
            EmailTemplate::$emailType($code)
        );
    }

    private static function getAccess(string $id) : object
    {
        global $timeZone;

        $access = new Jwt($id, ACCESS_KEY);
        $refresh = new Jwt($id, REFRESH_KEY);

        return (object)[
            'access_token' => $access->token,
            'refresh_token' => $refresh->token,
            'expiration_date' => $access->expiration_date,
            'time_zone' => $timeZone
        ];
    }

    private static function passwordValidate($body, $user) : void
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
