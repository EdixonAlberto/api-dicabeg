<?php

namespace V2\Modules;

use V2\Library\Gui;
use V2\Database\Querys;
use V2\Modules\Validations;

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

    public static function verifySession()
    {
        $sessionQuery = new Querys('sessions');

        $token = Validations::token();

        $session = $sessionQuery->select('api_token', $token, 'api_token, expiration_time');
        if ($session == false) throw new \Exception('token incorrect', 401);

        $activeSession = Sessions::validateExpiration($session);
        if ($activeSession == false) throw new \Exception('token expired', 401);
    }
}
