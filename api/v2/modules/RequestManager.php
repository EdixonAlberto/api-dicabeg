<?php

namespace V2\Modules;

use Exception;
use V2\Interfaces\IRequest;

class RequestManager implements IRequest
{
    protected static function getHeader(): object
    {
        $headers = (object) [];

        foreach (self::HEADERS as $key) {
            $value = $_SERVER['HTTP_' . $key] ?? false;
            if ($value) $headers->$key = $value;
        }
        return $headers;
    }

    protected static function getParams(object $params): object
    {
        if (isset($params->keys)) {
            $i = 0;
            $arrayKeys = $params->keys;
            $arrayValues = $params->value;
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
