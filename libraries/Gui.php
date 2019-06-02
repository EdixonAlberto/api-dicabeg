<?php

namespace V2\Libraries;

use Exception;

class Gui
{
    /**
        GUI v4
     */
    public static function generate() : string
    {
        $code = sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 65535),
            mt_rand(0, 65535),
            mt_rand(0, 65535),
            mt_rand(16384, 20479),
            mt_rand(32768, 49151),
            mt_rand(0, 65535),
            mt_rand(0, 65535),
            mt_rand(0, 65535)
        );
        return $code;
    }

    public static function validate(string $gui) : bool
    {
        if (preg_match(
            '/^[a-z0-9]{8}(\-[a-z0-9]{4}){3}\-[a-z0-9]{12}$/',
            $gui
        )) return true;
        else throw new Exception('id incorrect', 400);
    }
}
