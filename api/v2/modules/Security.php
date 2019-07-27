<?php

namespace V2\Modules;

use Exception;
use V2\Libraries\Gui;

class Security
{
    private static $algo = PASSWORD_DEFAULT;
    private static $options = ['cost' => 11];
    private const MIN_LENGTH = 8;
    private const MAX_LENGTH = 15;

    public static function generatePass($pass)
    {
        // $pass = $data ?: $data . time() . rand();

        if (is_null($pass)) throw new Exception('password is not set', 400);
        elseif (
            strlen($pass) >= self::MIN_LENGTH
            and strlen($pass) <= self::MAX_LENGTH
        ) return password_hash($pass, self::$algo, self::$options);
        else throw new Exception(
            "{self::MIN_LENGTH} <= password <= self::MAX_LENGTH",
            400
        );
    }

    public static function rehash($hash)
    {
        $needsRehash = password_needs_rehash($hash, self::$algo, self::$options);
        return $needsRehash ? self::generatePass($hash, true) : false;
    }

    public static function generateID()
    {
        $code = Gui::generate();
        return trim($code, '{}');
    }

    public static function generateCode(int $codeLength): string
    {
        // ADD: para asegurar la unicidad en los codigos de activacion, se debe
        // poner vencimiento a los mismos por medio de un trigger en la DB
        $code = Gui::generate();
        $code = preg_replace('|-|', '', $code);
        $code = strtoupper($code);
        return substr($code, 0, $codeLength);
    }
}
