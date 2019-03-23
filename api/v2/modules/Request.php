<?php

namespace V2\Modules;

require '../routes/config.php';
use V2\Modules\Validate;

class Request
{
    public static function validate()
    {
        $uri = $_SERVER['REQUEST_URI'];

        try {
            foreach ($arrayPattern as $pattern) {
                $validate = preg_match(
                    $pattern,
                    $uri,
                    $arrayRoute
                );
                if ($validate) {
                    array_shift($arrayRoute);

                    foreach ($arrayRoute as $key => $route) {
                        if (strlen($route) == 36) {
                            Validate::gui($route);
                            $idName = strtoupper($arrayRoute[$key - 1]) . '_ID';
                            define($idName, $route);
                        } else Validate::resource($route, $arrayResources);
                    }
                    define('RESOURCE', $arrayRoute[$key - 1]);
                    break;
                }
            }
        } catch (\Exception $error) {

        }

        return $validate;
    }
}
