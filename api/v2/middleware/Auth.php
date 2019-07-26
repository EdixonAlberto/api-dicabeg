<?php

namespace V2\Middleware;

use Exception;
use V2\Modules\User;
use V2\Libraries\Jwt;

class Auth
{
    public static $id;
    public static $name;

    public static function execute(object $headers): string
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
        } else throw new Exception('unauthorized access: token require', 401);

        $payload = Jwt::verific($token, $key);

        new User($payload->sub);

        self::$id = User::$id;
        self::$name = 'Estimado usuario'; // TODO: colocar el nombre y apellido del usuario

        if (User::$activated) return User::$id;
        else throw new Exception('account not activated', 403);
    }
}
