<?php

namespace V2\Modules;

use V2\Routes\Config;
use V2\Modules\Validate;

class Request extends Config
{
    public static function validate()
    {
        $routesConfig = new Config;
        $uri = preg_replace('/\/v2/', '', $_SERVER['REQUEST_URI']);

        foreach ($routesConfig->arrayRequest as $request) {
            $validate = preg_match(
                $request,
                $uri,
                $arrayRoute
            );

            if ($validate) {
                array_shift($arrayRoute);

                $i = 1;
                foreach ($arrayRoute as $key => $route) {
                    if (strlen($route) == 36) {
                        Validate::gui($route);
                        $idName = strtoupper($arrayRoute[$key - 1]) . '_ID';
                        define($idName, $route);
                    } else {
                        $resource = $route;
                        $existResource = in_array($resource, $routesConfig->arrayResource);
                        if ($existResource == false) throw new \Exception('request incorrect', 400);
                    };
                }

                $request = preg_replace('/[A-Z0-9-]{36}/', 'id', $uri);

                define('REQUEST', $request);
                define('RESOURCE', $resource);
                define('METHOD', $_SERVER['REQUEST_METHOD']);
                parse_str(file_get_contents('php://input'), $_REQUEST);

                break;
            }
        }
        if (!$validate) throw new \Exception('request incorrect', 400);
    }
}
