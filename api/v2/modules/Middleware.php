<?php

namespace V2\Modules;

use Exception;
use V2\Middleware\Auth;
use V2\Interfaces\IResource;
use V2\Middleware\InputData;
use V2\Middleware\Resources;
use V2\Middleware\FilterOutput;
use V2\Middleware\ActivatedAccount;

class Middleware implements IResource
{
    public static function input($body)
    {
        switch (RESOURCE) {
            case 'users':
                InputData::validate($body, self::USERS_COLUMNS);
                break;

            case 'videos':
                InputData::validate($body, self::VIDEOS_COLUMNS);
                break;
        }
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

    public static function activation($user_id)
    {
        ActivatedAccount::verific($user_id);
    }
}
