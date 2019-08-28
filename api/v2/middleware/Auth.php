<?php

namespace V2\Middleware;

use Exception;
use V2\Modules\User;
use V2\Libraries\Jwt;

class Auth
{
    private const ROLES = [
        1 => 'client',
        2 => 'enterprise',
        3 => 'admin'
    ];

    public static function execute(object $headers, $rol = null): string
    {
        if (isset($headers->ACCESS_TOKEN)) {
            $token = $headers->ACCESS_TOKEN;
            $key = ACCESS_KEY;
        } elseif (isset($headers->REFRESH_TOKEN)) {
            $token = $headers->REFRESH_TOKEN;
            $key = REFRESH_KEY;
            Jwt::extraTime(2);
        } else throw new Exception('unauthorized access: token require', 401);

        $payload = Jwt::verific($token, $key)->sub;

        if ($rol) {
            if ($rol != $payload->rol)
                throw new Exception(
                    'unauthorized access: require rol = ' . self::ROLES[$rol],
                    401
                );
        }

        new User($payload->id);

        if (User::$activated) return User::$id;
        else throw new Exception('account not activated', 403);
    }
}
