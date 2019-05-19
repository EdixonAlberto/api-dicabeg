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

    public static function output($response = null)
    {
        if (is_array($response)) {
            foreach ($response as $key => $value) {
                $newResponse[$key] = is_object($value) ?
                    Output::filter($value) : $value;
            }

        } elseif (is_object($response))
            $newResponse = Output::filter($response);

        else return $response;

        return $newResponse;
    }

    public static function authetication() : void
    {
        if ($_SERVER['HTTP_ACCESS_TOKEN'] ?? false) {
            $token = $_SERVER['HTTP_ACCESS_TOKEN'];
            $key = ACCESS_KEY;

        } elseif ($_SERVER['HTTP_REFRESH_TOKEN'] ?? false) {
            $token = $_SERVER['HTTP_REFRESH_TOKEN'];
            $key = REFRESH_KEY;
        }

        if ($token == false) throw new Exception(
            'requires a token to access this resource',
            400
        );
        new Auth($token, $key);
    }

    public static function activation($user_id)
    {
        Account::activationVerific($user_id);
    }

    public static function field($condition, $value) : bool
    {
        return Resource::validate($condition, $value);
    }
}
