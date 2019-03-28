<?php

namespace V2\Modules;

use V2\Libraries\Jwt;

class Auth
{
    public static function prosecute()
    {
        $email = $_REQUEST['email'] ?? false;
        $username = $_REQUEST['username'] ?? false;

        if ($email) {
            $user = Querys::table('users')
                ->select('user_id, email, password')
                ->where('email', $email)
                ->get();

            // $user = $userQuery->select('email', $email, 'user_id, email, password');
            if ($user == false) throw new \Exception('email not exist', 404);
        } elseif ($username) {
            $user = Querys::table('users')
                ->select('user_id, email, password')
                ->where('username', $username)
                ->get();
            // $user = $userQuery->select('username', $username, 'user_id, email, password');
            if ($user == false) throw new \Exception('username not exist', 404);
        }
    }
}
