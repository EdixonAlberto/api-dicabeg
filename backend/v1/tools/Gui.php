<?php

namespace Tools;

class Gui
{
    /**
        gui v4
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
        $result = trim($code, '{}');

        return $result;
    }
}
