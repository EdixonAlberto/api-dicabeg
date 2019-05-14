<?php

namespace V2\Middleware;

class Output
{
    private static $denials = [
        'user_id',
        'password'
    ];

    public static function filter(array $response) : array
    {
        foreach (self::$denials as $denied) {
            unset($response[$denied]);
        }

        foreach ($response as $key => $value) {
            if (is_null($value)) unset($response[$key]);
        }

        return $response;
    }
}
