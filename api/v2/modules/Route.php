<?php

namespace V2\Modules;

class Route
{
    public static function get(string $route, $callback) : void
    {
        if (METHOD == 'GET') {
            if (ROUTE == $route) {
                Middleware::authetication();
                $callback();
            }
        }
    }

    public static function post(string $route, $callback)
    {
        global $request;

        if (METHOD == 'POST') {
            if (ROUTE == $route) {
                if (RESOURCE != 'users' and RESOURCE != 'accounts')
                    Middleware::authetication();
                $callback($request);
            }
        }
    }

    public static function patch(string $route, $callback)
    {
        global $request;

        if (METHOD == 'PATCH') {
            if (ROUTE == $route) {
                Middleware::authetication();
                $callback($request);
            }
        }
    }

    public static function delete(string $route, $callback)
    {
        if (METHOD == 'DELETE') {
            if (ROUTE == $route) {
                Middleware::authetication();
                $callback();
            }
        }
    }
}
