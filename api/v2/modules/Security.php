<?php

namespace V2\Modules;

use Db\Querys;
use V2\Libraries\Gui;

class Security
{
    private static $algo = PASSWORD_DEFAULT;
    private static $options = ['cost' => 11];

    public static function generateHash($data = false)
    {
        $pass = $data ? : $data . time() . rand();
        return password_hash($data, self::$algo, self::$options);
    }

    public static function rehash($hash)
    {
        $needsRehash = password_needs_rehash($hash, self::$algo, self::$options);
        return $needsRehash ? self::generateHash($hash, true) : false;
    }

    public static function generateID()
    {
        $code = Gui::generate();
        return trim($code, '{}');
    }

    public static function generateCode(int $codeLength) : string
    {
        // ADD: para asegurar la unicidad en los codigos de activacion, se debe
        // poner vencimiento a los mismos por medio de un trigger en la DB
        $code = Gui::generate();
        $code = preg_replace('|-|', '', $code);
        $code = strtoupper($code);
        return substr($code, 0, $codeLength);
    }
}
