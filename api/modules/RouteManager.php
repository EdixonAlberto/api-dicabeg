<?php

namespace V2\Modules;

use Exception;
use V2\Interfaces\IRequest;

class RouteManager implements IRequest
{
    private static $uri;
    protected static $resource;
    protected static $queryParams;
    public $router;

    public function __construct()
    {
        $uri = $_SERVER['REQUEST_URI'];

        $resourcesDir = '../routes/' . $this->getService($uri);
        $resourceFile = $this->getResource($resourcesDir);

        $this->router = "{$resourcesDir}/{$resourceFile}";
    }

    public static function getService($uri): string
    {
        $service = '';

        self::$uri = '/' . preg_replace_callback(
            '/^\/(api|web)\//',
            function ($arrayServices) use (&$service) {
                [, $service] = $arrayServices;
            },
            $uri
        );

        if ($service) return $service;
        else throw new Exception('service incorrect', 400);
    }

    public static function getResource($resourcesDir): string
    {
        $resourceCorrect = preg_match('/^\/([a-z]+)\/?/', self::$uri, $arrayResources);
        // var_dump($arrayResources); // DEBUG:

        if ($resourceCorrect) {
            [, $resource] = $arrayResources;
            $resource = trim($resource, 's');

            $resourceFile = "{$resource}Route.php";

            if (in_array($resourceFile, scandir($resourcesDir))) {
                self::$resource = $resource;
                return $resourceFile;
            }
        }
        throw new Exception('resource not exist', 400);
    }

    protected static function getMethod(): string
    {
        $method = $_SERVER['REQUEST_METHOD'];
        if (in_array($method, self::METHODS)) return $method;
        else throw new Exception('method not support', 405);
    }

    protected function controllerValidate(string $controller): bool
    {
        $controllerName = substr($controller, 0, strpos($controller, ':'));
        $controllerFile =  $controllerName . '.php';
        $controllersDir = '../controllers';

        return in_array($controllerFile, scandir($controllersDir));
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
        // var_dump(self::$uri, $route, $validation, $queryParams); // DEBUG:

        self::$queryParams = $queryParams;

        return $validation;
    }
}
