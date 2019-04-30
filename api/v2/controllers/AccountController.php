<?php

namespace V2\Controllers;

use Exception;
use V2\Libraries\Jwt;
use V2\Database\Querys;
use V2\Modules\Security;
use V2\Modules\JsonResponse;

class AccountController
{
    public static function activateAccount($body)
    {
        $codeQuery = Querys::table('codes');

        $activationCode = $codeQuery->select('activation_code')
            ->where('activation_code', $body->activation_code)
            ->get();

        if ($activationCode == 'active')
            JsonResponse::OK('the account is already active');

        elseif ($activationCode == $body->activation_code) {
            $codeQuery->update([
                'activation_code' => 'active'
            ])->where('user_id', USERS_ID)->execute();

        } else throw new Exception('invalited code', 403);

        JsonResponse::OK('activated account');
    }

    public static function userLogin($body)
    {
        $userQuery = Querys::table('users')
            ->select(['user_id', 'activated_account', 'password']);

        if (isset($body->email)) {
            $user = $userQuery->where('email', $body->email)->get();
        } elseif (isset($body->username)) {
            $user = $userQuery->where('username', $body->username)->get();
        } else throw new Exception('denied authorization', 403);

        if ($user->activated_account == false)
            throw new Exception('disabled account', 403);

        self::passwordValidate($body, $user);

        $token = Jwt::create($user->user_id);

        $path = 'https://' . $_SERVER['SERVER_NAME'] . '/v2/users';
        JsonResponse::created('api_token', $token, $path);
    }

    public static function passwordRecovery()
    {

    }

    private static function passwordValidate($body, $user)
    {
        if (!isset($body->password)) throw new Exception('passsword is not set', 401);

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
