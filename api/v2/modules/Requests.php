<?php

namespace V2\Modules;

use Exception;
use V2\Libraries\Gui;
use V1\Users\Referrals\Referrals;

class Requests
{
    private const RESOURCES = [
        'users',
        'accounts',
        'referrals',
        'videos',
        'history',
        'transfers'
    ];

    private const PATTERNS = [
        '|/([a-z]+)/(.*)/([a-z]+)/(.*)|',  // /route1/id1/route2/id2
        '|/([a-z]+)/(.*)/([a-z]+)|',                  // /route1/id1/route2
        '|/([a-z]+)/group/([0-9]{1,})|',                         // /route1/group/nro
        '|/([a-z]+)/(.*)|',                           // /route1/id1
        '|/([a-z]+)|',                                           // /route1
    ];

    public function __construct()
    {
        $url = preg_replace('|/v2|', '', $_SERVER['REQUEST_URI']);

        foreach (self::PATTERNS as $pattern) {
            $validated = preg_match(
                $pattern,
                $url,
                $arrayRequest
            );

            // var_dump($arrayRequest);

            if ($validated) {
                array_shift($arrayRequest);

                foreach ($arrayRequest as $index => $request) {
                    if (strlen($request) == 36 or strlen($request) == 14) {
                        if (strlen($request) == 36) {
                            Gui::validate($request);
                            $route = preg_replace('/[a-z0-9-]{36}/', 'id', $url);

                        } else $route = preg_replace('/[A-Z0-9]{14}/', 'id', $url);

                        $idName = strtoupper($resource) . '_ID';
                        define($idName, $request);

                        $resource = $arrayRequest[$index - 1];

                    } elseif (is_numeric($request)) {
                        define('GROUP_NRO', $request);
                        $resource = $arrayRequest[$index - 1];
                        $route = preg_replace('/[0-9]{1,}/', 'nro', $url);

                    } elseif (empty($resource))
                        $resource = $arrayRequest[$index];
                }

                // var_dump($resource, $route);
                if (in_array($resource, self::RESOURCES)) {
                    define('RESOURCE', $resource);

                    if (isset($route)) define('ROUTE', $route);
                    else define('ROUTE', $url);

                    define('METHOD', $_SERVER['REQUEST_METHOD']);

                    parse_str(file_get_contents('php://input'), $_body);
                    $this->body = (object)$_body;
                    break;

                } else {
                    $validated = false;
                    break;
                }
            }
        }
        if (!$validated) throw new Exception('request incorrect', 400);
    }
}
