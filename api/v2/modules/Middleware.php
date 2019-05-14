<?php

namespace V2\Modules;

use Exception;
use V2\Middleware\Auth;
use V2\Middleware\Input;
use V2\Middleware\Output;
use V2\Middleware\Account;
use V2\Middleware\Resource;
use V2\Interfaces\IResource;

class Middleware implements IResource
{
    public static function input($body)
    {
        switch (RESOURCE) {
            case 'users':
                Input::validate($body, self::USERS_COLUMNS);
                break;

            case 'videos':
                Input::validate($body, self::VIDEOS_COLUMNS);
                break;
        }
    }

    public static function output(array $response = null)
    {
        if (is_array($response)) {
            if (isset($response[0])) {
                foreach ($response as $key => $value) {
                    $newResponse[$key] = Output::filter($value);
                }
            } else $newResponse = Output::filter($response);

        } else return $response;

        return $newResponse;
    }

    public static function authetication()
    {
        $token = $_SERVER['HTTP_API_TOKEN'] ?? false;

        if ($token == false) throw new Exception(
            'requires a token to access this resource',
            // 'header [api_token] is not set',
            400
        );
        new Auth($token);
    }

    public static function activation($user_id)
    {
        Account::activationVerific($user_id);
    }

    public static function field($condition, $value) : value
    {
        return Resource::validate($condition, $value);
    }
}
