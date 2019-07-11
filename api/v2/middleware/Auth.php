<?php

namespace V2\Middleware;

use Exception;
use V2\Libraries\Jwt;
use V2\Database\Querys;

class Auth
{
    public static $id;

    public static function execute(object $headers) : string
    {
        if (isset($headers->ACCESS_TOKEN)) {
            $token = $headers->ACCESS_TOKEN;
            $key = ACCESS_KEY;
            unset($headers->ACCESS_TOKEN);

        } elseif (isset($headers->REFRESH_TOKEN)) {
            $token = $headers->REFRESH_TOKEN;
            $key = REFRESH_KEY;
            Jwt::extraTime(2);
            unset($headers->REFRESH_TOKEN);


        } else throw new Exception('unauthorized access: token require', 401); //ERROR:

        $payload = Jwt::verific($token, $key);

        $user = Querys::table('users')
            ->select(['user_id', 'activated'])
            ->where('user_id', $payload->sub)->get();

        if ($user->activated) return self::$id = $user->user_id;
        else throw new Exception('account not activated', 403);
    }
}
