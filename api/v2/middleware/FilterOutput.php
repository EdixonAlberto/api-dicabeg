<?php

namespace V2\Middleware;

class FilterOutput
{
    private static $denials = [
        'user_id',
        'player_id',
        'password'
    ];

    public static function process($response)
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
