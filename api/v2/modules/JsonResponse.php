<?php

namespace V2\Modules;

class JsonResponse
{
    public static function read($content) : void
    {
        $response = [
            'status' => 200,
            'response' => 'successful',
            'description' => 'found resource',
            'resource' => [
                RESOURCE => Middleware::output($content)
            ],
        ];
        self::send($response);
    }

    public static function created(
        $content,
        string $path = null,
        $info = null
    ) : void {

        $response = [
            'status' => 201,
            'response' => 'successful',
            'description' => 'created resource',
            'resource' => [
                RESOURCE => Middleware::output($content)
            ],
            'path' => $path,
            'information' => Middleware::output($info)
        ];
        self::send($response, 201);
    }

    public static function updated(
        $content,
        string $info = null
    ) : void {

        $response = [
            'status' => 200,
            'response' => 'successful',
            'description' => 'updated resource',
            'resource' => [
                RESOURCE => Middleware::output($content)
            ],
            'information' => $info
        ];
        self::send($response);
    }

    public static function removed() : void
    {
        $response = [
            'status' => 200,
            'response' => 'successful',
            'description' => 'deleted resource',
        ];
        self::send($response);
    }

    public static function OK($content) : void
    {
        $response = [
            'status' => 200,
            'response' => 'successful',
            'description' => $content
        ];
        self::send($response);
    }

    public static function error($content, int $code) : void
    {
        $response = [
            'status' => $code,
            'response' => 'error',
            'description' => $content
        ];
        self::send($response, $code);
    }

    private static function send(array $response, int $code = 200) : void
    {
        header("Content-Type: application/json; charset=UTF-8");
        http_response_code($code);
        die(json_encode($response));
    }
}
