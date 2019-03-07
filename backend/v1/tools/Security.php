<?php

namespace Tools;

use Lib\Gui;

class Security
{
    private const ALGO = PASSWORD_DEFAULT;
    private const OPTIONS = ['cost' => 11];

    public static function generateHash($data = false)
    {
        $pass = ($data == false) ?
            $_REQUEST['password'] :
            $data . date('Y-m-d H:i:s') . rand();
        return password_hash($pass, self::ALGO, self::OPTIONS);
    }

    public static function rehash($hash)
    {
        $needsRehash = password_needs_rehash($hash, self::ALGO, self::OPTIONS);
        return $needsRehash ? self::generateHash($hash, true) : false;
    }

    public static function generateID()
    {
        $code = Gui::generate();
        return trim($code, '{}');
    }

    public static function generateCode()
    {
        $code = Gui::generate();
        $code = preg_replace('/-/', '', $code);
        return substr($code, 0, 8);
    }
}
