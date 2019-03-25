<?php

namespace V2\Modules;

use V2\Modules\Request;

class Route
{
    public static function get($route, $controller)
    {
        if (METHOD == 'GET') {
            if (REQUEST == $route) {
                $existID = strrpos($route, 'id') > 0;
                if ($existID) $controller::show();
                else $controller::index();
            }
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
