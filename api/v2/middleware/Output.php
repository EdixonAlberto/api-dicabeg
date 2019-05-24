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
        'transfer_nro'
    ];

    public static function filter($response) : object
    {
        foreach (self::$denials as $denied) {
            unset($response->$denied);
        }

        foreach ($response as $key => $value) {
            if (is_null($value)) unset($response->$key);

            $numericFormated = (is_numeric($value) and
                !in_array($key, self::$stringField));

            if ($numericFormated) $response->$key = Format::number($value);
        }

        return $response;
    }
}
