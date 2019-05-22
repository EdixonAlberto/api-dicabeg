<?php

namespace V2\Modules;

class Route
{
    public static function get(string $route, $callback) : void
    {
        if (METHOD == 'GET') {
            if (ROUTE == $route) {
                $callback();
            }
        }
    }

    public static function post(string $route, $callback)
    {
        global $request;

        if (METHOD == 'POST') {
            if (ROUTE == $route) {
                $callback($request);
            }
        }
    }

    public static function patch(string $route, $callback)
    {
        global $request;

        if (METHOD == 'PATCH') {
            if (ROUTE == $route) {
                $callback($request);
            }
        }
    }

    public static function delete(string $route, $callback)
    {
        if (METHOD == 'DELETE') {
            if (ROUTE == $route) {
                $callback();
            }
        }
    }
}
