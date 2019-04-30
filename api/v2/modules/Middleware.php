<?php

namespace V2\Modules;

use V2\Middleware\Auth;
use V2\Middleware\InputData;
use V2\Middleware\Resources;
use V2\Middleware\FilterOutput;

class Middleware
{
    public static function authetication()
    {
        $token = $_SERVER['HTTP_API_TOKEN'] ?? false;
        if ($token == false) throw new Exception(
            'header [api_token] is not set',
            400
        );

        new Auth($token);
    }

    public static function input($request)
    {
        Resources::validate($request->resource);
        InputData::validate($request);
    }

    public static function output($response)
    {
        if (is_array($response)) {
            foreach ($response as $key => $resp) {
                $newResponse[$key] = FilterOutput::process($resp);
            }

        } else $newResponse = FilterOutput::process($response);

        return $newResponse;
    }
}
