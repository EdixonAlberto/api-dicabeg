<?php

namespace V2\Modules;

use V2\Interfaces\IRequest;

class RouteManager implements IRequest
{
    private static $uri;
    protected static $resource;
    protected static $queryParams;

    public function __construct()
    {
        self::$uri = preg_replace('/^\/api/', '', $_SERVER['REQUEST_URI']);
        // \var_dump(self::$uri); // DEBUG:
    }

    public static function getResource(): string
    {
        $resourceFound = preg_match('/^\/([a-z]+)\/?/', self::$uri, $arrayResource);
        // \var_dump($arrayResource); // DEBUG:
        if ($resourceFound) {
            if (in_array($arrayResource[1], self::RESOURCES)) {
                self::$resource = trim($arrayResource[1], 's');
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

    protected static function getHeader(): object
    {
        $headers = (object) [];

        foreach (self::HEADERS as $key) {
            $value = $_SERVER['HTTP_' . $key] ?? false;
            if ($value) $headers->$key = $value;
        }
        return $headers;
    }

    protected function routeValidate(string $route): bool
    {
        $queryParams = (object) [];

        preg_replace_callback(
            '/\{([a-zA-Z0-9]+)\}/', // Patron de busqueda para parametros get
            function ($arrayGet) use (&$route, &$queryParams) {
                $get = $queryParams->keys[] = $arrayGet[1];
                $route = preg_replace("/\{$get\}/", '([a-zA-Z0-9-]+)', $route);
            },
            $route
        );

        $validation = preg_match(
            '|^' . $route . '$|',
            self::$uri,
            $queryParams->value
        );
        // var_dump(self::$uri, $route, $validation); // DEBUG:
        // var_dump($queryParams); // DEBUG:

        self::$queryParams = $queryParams;

        return $validation;
    }
}
