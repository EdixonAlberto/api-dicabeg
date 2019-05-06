<?php

namespace V2\Middleware;

use Exception;
use V2\Database\Querys;

class Resources
{
    public function validate()
    {
        switch (RESOURCE) {

            case 'users':
                Querys::table('users')
                    ->select('user_id')
                    ->WHERE('user_id', USERS_ID)
                    ->get(function ($err) {
                        if ($err) throw new Exception(
                            'user not exist',
                            404
                        );
                    });
                break;

            case 'referrals':
                Querys::table('referrals')
                    ->select('referred_id')
                    ->WHERE('referred_id', REFERRALS_ID)
                    ->get(function ($err) {
                        if ($err) throw new Exception(
                            'referred not exist',
                            404
                        );
                    });
                break;

            case 'history':

                break;

            case 'videos':

                break;
        }
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