<?php

namespace V2\Middleware;

use Exception;
use V2\Database\Querys;

class Account
{
    public static function activationVerific($user_id)
    {
        $activatedAccount = Querys::table('accounts')
            ->select('activated_account')
            ->where('user_id', $user_id)
            ->get();

        if ($activatedAccount == false)
            throw new Exception('disabled account', 403);
    }
}
