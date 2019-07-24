<?php

namespace V2\Modules;

use V2\Interfaces\IRequest;

class RouteManager implements IRequest
{
    private static $uri;
    protected $parameters;
    protected static $resource;

    public function __construct()
    {
        self::$uri = preg_replace('/\/v2/', '', $_SERVER['REQUEST_URI']);
        $this->parameters = (object) [];
    }

    public static function getResource(): string
    {
        $resourceFound = preg_match('/[a-z]+/', self::$uri, $arrayResource);

        if ($resourceFound) {
            if (in_array($arrayResource[0], self::RESOURCES)) {
                self::$resource = trim($arrayResource[0], 's');
                return self::$resource;
            }
        }
        return '';
    }

    protected static function getMethod(): string
    {
        $method = $_SERVER['REQUEST_METHOD'];
        if (in_array($method, self::METHODS)) return $method;

        else return '';
    }

    protected function routeValidate(string $route): bool
    {
        preg_replace_callback(
            '/\{([a-z0-9]+)\}/',
            function ($arrayGet) use (&$route) {
                $validation = true;
                $get = $this->parameters->keys[] = $arrayGet[1];
                $route = preg_replace("/\{$get\}/", '([a-zA-Z0-9-]+)', $route);
            },
            $route
        );
        // var_dump($route, self::$uri);
        $validation = preg_match(
            '|^' . $route . '$|',
            self::$uri,
            $this->parameters->value
        );
        return $validation;
    }
}
