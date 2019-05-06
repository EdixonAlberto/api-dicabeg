<?php

namespace V2\Middleware;

use Exception;
use V2\Database\Querys;

class ActivatedAccount
{
    public static function verific($user_id)
    {
        $activatedAccount = Querys::table('accounts')
            ->select('activated_account')
            ->where('user_id', $user_id)
            ->get();

        if ($activatedAccount == false)
            throw new Exception('disabled account', 403);
    }
}
