<?php

namespace Modules\Tools;

use Libraries\Gui;
use Modules\Exceptions;
use V2\Database\Querys;

class Code
{
    private const CODE_LENGTH = 6;

    public static function create(): string
    {
        $code = Gui::create();
        $code = preg_replace('|-|', '', $code);
        $code = strtoupper($code);
        return substr($code, 0, self::CODE_LENGTH);
    }

    public static function validate(string $inputCode, string $email): bool
    {
        $account = Querys::table('accounts')
            ->select(['temporal_code', 'code_create_date'])
            ->where('email', $email)
            ->get(function () {
                new Exceptions\NotFound('email');
            });

        $savedCode = $account->temporal_code;

        if ($savedCode  == 'expire')
            new Exceptions\CodeError($savedCode, $account->code_create_date);
        elseif ($savedCode  == 'used')
            new Exceptions\CodeError($savedCode, $account->code_create_date);
        elseif ($savedCode !== $inputCode)
            new Exceptions\CodeError('incorrect', $inputCode);
        else return true;
    }
}
