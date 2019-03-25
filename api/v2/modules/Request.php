<?php

namespace V2\Modules;

use V2\Routes\Config;

class Request extends Config
{
    public static function validate()
    {
        $config = new Config;
        $uri = preg_replace('/\/v2/', '', $_SERVER['REQUEST_URI']);

        foreach ($config->arrayRequest as $request) {
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
                        $idName = strtoupper($arrayRoute[$key - 1]) . '_ID';
                        define($idName, $route);
                        define('ID' . $i++, $route);
                    } else {
                        $resource = $route;
                        $existResource = in_array($resource, $config->arrayResource);
                        if ($existResource == false) throw new \Exception('request incorrect', 400);
                    };
                }

                $request = preg_replace('/[A-Z0-9-]{36}/', 'id', $uri);

                define('REQUEST', $request);
                define('RESOURCE', $resource);
                define('METHOD', $_SERVER['REQUEST_METHOD']);

                // var_dump($uri, $resource, $request);

                break;
            }
        }
        if (!$validate) throw new \Exception('request incorrect', 400);
    }
}
