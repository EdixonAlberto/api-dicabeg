<?php

namespace V2\Modules;

use Exception;
use V2\Libraries\Gui;

class Requests
{
    private const RESOURCES = [
        'users',
        'accounts',
    ];

    private const PATTERNS = [
        '|/([a-z]+)/([A-Z0-9-]{36})/([a-z]+)/([A-Z0-9-]{36})|',  // /route1/id1/route2/id2
        '|/([a-z]+)/([A-Z0-9-]{36})/([a-z]+)|',                  // /route1/id1/route2
        '|/([a-z]+)/group/([0-9]{1,})|',                         // /route1/group/nro
        '|/([a-z]+)/([a-z]+)|',                                  // /route1/route2
        '|/([a-z]+)/([A-Z0-9-]{36})|',                           // /route1/id1
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

            if ($validated) {
                array_shift($arrayRequest);

                foreach ($arrayRequest as $index => $request) {
                    if (strlen($request) == 36) {
                        Gui::validate($request);
                        $idName = strtoupper($resource) . '_ID';
                        define($idName, $request);
                        $resource = $arrayRequest[$index - 1];

                    } elseif (is_numeric($request)) {
                        define('GROUP_NRO', $request);
                        $resource = $arrayRequest[$index - 1];

                    } elseif (empty($resource))
                        $resource = $arrayRequest[$index];
                }
                if (in_array($resource, self::RESOURCES)) {
                    $this->resource = $resource;

                    parse_str(file_get_contents('php://input'), $body);
                    $this->body = (object)$body;

                    if (isset($idName))
                        $route = preg_replace('/[A-Z0-9-]{36}/', 'id', $url);
                    else $route = preg_replace('/[0-9]{1,}/', 'nro', $url);

                    define('ROUTE', $route);
                    define('METHOD', $_SERVER['REQUEST_METHOD']);

                    break;
                } else $validated = false;
            }
        }
        if (!$validated) throw new Exception('request incorrect', 400);
    }
}
