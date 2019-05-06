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
        $pass = $data ? : $data . date('Y-m-d H:i:s') . rand();
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

    public static function generateCode(int $codeLength)
    {
        $code = Gui::generate();
        $code = preg_replace('|-|', '', $code);
        $code = strtoupper($code);
        return substr($code, 0, $codeLength);
    }
}
