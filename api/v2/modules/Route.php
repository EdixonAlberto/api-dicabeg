<?php

namespace V2\Modules;

use Exception;
use V2\Modules\Requests;
use V2\Modules\RouteManager;

class Route extends RouteManager
{
    private const NAME_SPACE = 'V2\\Controllers\\';
    private const NAME_SPACE_MIDD = 'V2\\Middleware\\';
    private static $middleware;

    public static function midd(string $middleware): self
    {
        self::$middleware = $middleware;
        return (new Route);
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
            $request = new Requests($this->parameters);

            if (self::$middleware) call_user_func(
                self::NAME_SPACE_MIDD . self::$middleware . '::execute',
                $request->headers
            );

            call_user_func(
                self::NAME_SPACE . $controller,
                $request
            );
        } else self::$middleware = false;
    }
}
