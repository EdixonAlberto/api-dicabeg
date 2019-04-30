<?php

namespace V2\Middleware;

class FilterOutput
{
    private static $denials = [
        'player_id',
        'password'
    ];

    public static function process($response)
    {
        foreach (self::$denials as $denied) {
            unset($response->$denied);
        }
        return $response;
    }
}
