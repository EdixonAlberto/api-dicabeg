<?php

namespace V2\Middleware;

use Exception;
use V2\Database\Querys;

class Resource
{
    public function validate(string $condition, string $value) : bool
    {
        $user = Querys::table('users')
            ->select('user_id')
            ->WHERE($condition, $value)
            ->get();

        return $user ? true : false;
    }
}


// if (!isset($body->email))
//     throw new Exception("email is not set", 400);

// if (isset($body->username))
//     throw new Exception("username is not set", 400);

// $userQuery->WHERE('email', $body->email)
//     ->get(function ($err) {
//         if ($err) throw new Exception("email exist", 404);
//     });