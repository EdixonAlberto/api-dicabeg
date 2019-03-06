<?php

namespace Tools;

class Gui
{
    /**
        gui v4
     */
    public static function generate($type = null)
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
        switch ($type) {
            case 'id':
                return trim($code, '{}');
                break;
            case 'code':
                $code = preg_replace('/-/', '', $code);
                $code = substr($code, 0, 8);
                return $code;
                break;
            default:
                return $code;
        }
    }
}
