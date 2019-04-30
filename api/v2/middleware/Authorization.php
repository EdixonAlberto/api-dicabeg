<?php

namespace V2\Modules;

use Exception;
use V2\Libraries\Jwt;
use V2\Database\Querys;

class Authorization
{
    // public static function verific()
    // {
    //     $token = $_SERVER['HTTP_API_TOKEN'] ?? false;
    //     if ($token == false) self::newAuthorization();

    //     $payload = Jwt::process($token);

    //     Querys::table('users')
    //         ->select('user_id')
    //         ->where('user_id', $payload->sub)
    //         ->get(function ($user_id) {
    //             if ($user_id) define('USERS_ID', $user_id);
    //             else throw new Exception('token incorrect', 401);
    //         });
    // }

    public static function newAuthorization()
    {
        $body = (object)$_REQUEST;
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

        $apiToken = Jwt::create($user->user_id);

        $path = 'https://' . $_SERVER['SERVER_NAME'] . '/v2/users';
        JsonResponse::created('api_token', $apiToken, $path);
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
