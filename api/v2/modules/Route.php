<?php

namespace V2\Modules;

class Route
{
    public static function get($route, $controller)
    {
        if (METHOD == 'GET') {
            $controller::index();
        }
    }

    public static function post($route, $controller)
    {
    }

    public static function patch($route, $controller)
    {
    }

    public static function delete($route, $controller)
    {
    }
}
