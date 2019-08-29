<?php

namespace V2\Modules;

use Exception;
use V2\Interfaces\IRequest;
use V2\Modules\RouteManager;

class RequestManager extends RouteManager implements IRequest
{
    protected static function getHeader(): object
    {
        $headers = [];

        foreach (self::HEADERS as $key) {
            $value = $_SERVER['HTTP_' . $key] ?? false;
            if ($value) $headers[$key] = $value;
        }

        if (empty($headers)) throw new Exception('header required', 400);
        else return (object) $headers;
    }

    protected static function getParams(): object
    {
        if (isset(self::$queryParams->keys)) {
            $i = 0;
            $arrayKeys = self::$queryParams->keys;
            $arrayValues = self::$queryParams->value;
            array_shift($arrayValues);

            foreach ($arrayValues as $value) {
                $_params[$arrayKeys[$i++]] = $value;
            }
            return (object) $_params;
        } else return (object) [];
    }

    protected static function getBody()
    {
        parse_str(file_get_contents('php://input'), $body);
        return empty($body) ? false : (object) $body;
    }
}
