<?php

namespace V2\Modules;

use Exception;
use V2\Middleware\Auth;
use V2\Middleware\InputData;
use V2\Middleware\Resources;
use V2\Middleware\FilterOutput;
use V2\Middleware\ActivatedAccount;

class Middleware
{
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

        } elseif (is_object($response))
            $newResponse = FilterOutput::process($response);

        else return $response;

        return $newResponse;
    }

    public static function authetication()
    {
        $token = $_SERVER['HTTP_API_TOKEN'] ?? false;

        if ($token == false) throw new Exception(
            'header [api_token] is not set',
            400
        );
        new Auth($token);
    }

    public static function activation($body)
    {
        ActivatedAccount::verific($body->email);
    }
}
