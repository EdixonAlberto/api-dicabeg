<?php

namespace V2\Libraries;

class Gui
{
    /**
        GUI v4
     */
    public static function generate()
    {
        $code = sprintf(
            '%04X%04X-%04X-%04X-%04X-%04X%04X%04X',
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

    public static function validate(string $gui)
    {
        if (preg_match(
            '/^[A-Z0-9]{8}(\-[A-Z0-9]{4}){3}\-[A-Z0-9]{12}$/',
            $gui
        )) return true;
        else throw new \Exception('id incorrect', 400);
    }
}
