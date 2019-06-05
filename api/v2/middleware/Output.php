<?php

namespace V2\Middleware;

use V2\Modules\Format;

class Output
{
    private static $denials = [
        'user_id',
        'password'
    ];

    private static $stringField = [
        'transfer_code'
    ];

    public static function filter($response) : object
    {
        foreach ($response as $key => $value) {
            if (is_null($value) or $value === '')
                unset($response->$key);

            elseif (in_array($key, self::$denials))
                unset($response->$key);

            elseif (is_numeric($value) and !in_array($key, self::$stringField)) {
                $response->$key = Format::number($value);
            }
        }
        return $response;
    }
}
