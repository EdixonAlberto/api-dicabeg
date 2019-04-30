<?php

namespace V2\Modules;

class JsonResponse
{
    public static function read(string $content, $resource)
    {
        $response = [
            'status' => 200,
            'response' => 'successful',
            'description' => 'found resource',
            'resource' => [
                $content => Middleware::output($resource)
            ]
        ];
        self::send($response);
    }

    public static function created(
        string $content,
        $resource,
        string $path = null,
        array $info = null
    ) {
        $response = [
            'status' => 201,
            'response' => 'successful',
            'description' => 'created resource',
            'resource' => [
                $content => Middleware::output((object)$resource)
            ],
            'path' => $path,
            'information' => $info
        ];
        self::send($response, 201);
    }

    public static function updated(
        string $content,
        $resource,
        string $info = null
    ) {
        $response = [
            'status' => 200,
            'response' => 'successful',
            'description' => 'updated resource',
            'resource' => [
                $content => $resource
            ],
            'information' => $info
        ];
        self::send($response);
    }

    public static function removed()
    {
        $response = [
            'status' => 200,
            'response' => 'successful',
            'description' => 'deleted resource',
        ];
        self::send($response);
    }

    public static function OK(string $description)
    {
        $response = [
            'status' => 200,
            'response' => 'successful',
            'description' => $description
        ];
        self::send($response);
    }

    public static function error(string $description, int $code)
    {
        $response = [
            'status' => $code,
            'response' => 'error',
            'description' => $description,
        ];
        self::send($response, $code);
    }

    private static function send(array $response, int $code = 200)
    {
        header("Content-Type: application/json; charset=UTF-8");
        http_response_code($code);
        die(json_encode($response));
    }
}
