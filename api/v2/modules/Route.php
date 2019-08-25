<?php

namespace V2\Modules;

use V2\Modules\Requests;
use V2\Modules\RouteManager;

class Route extends RouteManager
{
    private const NAME_SPACE = '\V2\\Controllers\\';
    private const NAME_SPACE_MIDD = '\V2\\Middleware\\';
    private $middleware;

    public function __construct($midd = null)
    {
        if (is_array($midd)) {
            foreach ($midd as $class => $params) {
                $this->middleware = (object) [
                    'class' => $class,
                    'params' => $params
                ];
            }
        } elseif (is_string($midd))
            $this->middleware = (object) ['class' => $midd];
        else $this->middleware = null;
    }

    public static function midd($middleware): self
    {
        return (new Route($middleware));
    }

    public static function group($middleware, callable $routes): void
    {
        $route = new Route($middleware);
        $routes($route);
    }

    public static function __callStatic(string $verb, array $arguments): void
    {
        (new Route)->processRoute($verb, $arguments);
    }

    public function __call(string $verb, array $arguments): void
    {
        $this->processRoute($verb, $arguments);
    }

    private function processRoute(string $verb, array $arrayArguments): void
    {
        $requestMethod = strtoupper($verb);
        [$route, $controller] = $arrayArguments;
        $method = self::getMethod();

        $methodCorrect = ($method == $requestMethod);
        $routeCorrect = $this->routeValidate($route);

        if ($methodCorrect and $routeCorrect) {
            $request = new Requests;

            if ($this->middleware) call_user_func(
                self::NAME_SPACE_MIDD . $this->middleware->class . '::execute',
                $request->headers,
                $this->middleware->params ?? null
            );

            if (is_string($controller)) call_user_func(
                self::NAME_SPACE . $controller,
                $request
            );
            else $controller();
        }
    }
}
