<?php

namespace V2\Middleware;

use V2\Database\Querys;

class ActivatedAccount
{
    public static function verific($email)
    {
        $activatedAccount = Querys::table('account')
            ->select('activated_account')
            ->where('email', $email)
            ->get(function () {
                throw new Exception('email not found', 404);
            });

        if ($activatedAccount == false)
            throw new Exception('disabled account', 403);
    }
}
