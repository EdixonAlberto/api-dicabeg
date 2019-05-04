<?php

namespace V2\Middleware;

use Exception;
use V2\Libraries\Jwt;
use V2\Database\Querys;

class Auth
{
    public function __construct($token)
    {
        $payload = Jwt::process($token);

        $user_id = Querys::table('users')
            ->select('user_id')
            ->where('user_id', $payload->sub)
            ->get(function () {
                throw new Exception('token incorrect', 401);
            });

        define('USERS_ID', $user_id);
    }
}
