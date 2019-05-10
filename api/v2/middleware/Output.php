<?php

namespace V2\Middleware;

class Output
{
    private static $denials = [
        'user_id',
        'password'
    ];

    public static function filter($response)
    {
        $arrayResp = (array)$response;

        foreach (self::$denials as $denied) {
            unset($arrayResp[$denied]);
        }

        foreach ($arrayResp as $key => $value) {
            if (is_null($arrayResp[$key])) unset($arrayResp[$key]);
        }

        return $arrayResp;
    }
}
