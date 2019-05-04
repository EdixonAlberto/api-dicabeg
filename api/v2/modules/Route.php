<?php

namespace V2\Modules;

class Route
{
    public static function get(string $route, $controller)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            if (ROUTE == $route) {
                Middleware::authetication();
                $controller();
            }
        }
    }

    public static function post(string $route, $controller)
    {
        global $request;

        if (METHOD == 'POST') {
            if (ROUTE == $route) {
                if (RESOURCE != 'users' and RESOURCE != 'accounts')
                    Middleware::authetication();
                $controller($request);
            }
        }
    }

    public static function patch(string $route, $controller)
    {
        global $request;

        if (METHOD == 'PATCH') {
            if (ROUTE == $route) {
                Middleware::authetication();
                $controller($request);
            }
        }
    }

    public static function delete(string $route, $controller)
    {
        if (METHOD == 'DELETE') {
            if (ROUTE == $route) {
                Middleware::authetication();
                $controller();
            }
        }
    }
}
