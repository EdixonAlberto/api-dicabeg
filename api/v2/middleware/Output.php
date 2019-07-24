<?php

namespace V2\Middleware;

use V2\Modules\Format;

class Output
{
    private static $denials = [
        'password'
    ];

    private static $stringField = [
        'transfer_code',
        'invite_code'
    ];

    public static function filter($response): array
    {
        $response = (array) $response;

        foreach ($response as $key => $value) {
            if (is_array($value) or is_object($value))
                $response[$key] = self::filter($value);

            elseif (in_array($key, self::$denials))
                unset($response[$key]);

            elseif (is_null($value) or $value === '')
                unset($response[$key]);

            elseif (
                is_numeric($value) and
                !in_array($key, self::$stringField)
            ) {
                $response[$key] = Format::number($value);
            }
        }
        return $response;
    }
}
