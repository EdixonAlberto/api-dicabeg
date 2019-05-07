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
        foreach (self::$denials as $denied) {
            unset($response->$denied);
        }

        $arrayResp = (array)$response;
        foreach ($arrayResp as $key => $value) {
            if (is_null($arrayResp[$key])) unset($arrayResp[$key]);
        }
        $response = (object)$arrayResp;

        return $response;
    }
}
