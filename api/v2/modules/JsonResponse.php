<?php

namespace V2\Modules;

use V2\Middleware\Output;
use V2\Modules\RouteManager;

class JsonResponse extends RouteManager
{
    public static function read($content) : void
    {
        $response = [
            'status' => 200,
            'response' => 'successful',
            'description' => 'found resource',
            'resource' => [
                self::$resource => Output::filter($content)
            ],
        ];

        self::send($response);
    }

    public static function created(
        $content,
        string $path = null,
        string $info = null
    ) : void {

        $response = [
            'status' => 201,
            'response' => 'successful',
            'description' => 'created resource',
            'resource' => [
                self::$resource => Output::filter($content)
            ],
            'path' => $path
        ];

        if ($info) $response = array_merge(
            $response,
            ['information' => Output::filter($info)]
        );
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
                self::$resource => Output::filter($content)
            ]
        ];

        if ($info) $response = array_merge(
            $response,
            ['information' => Output::filter($info)]
        );
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

    public static function OK(
        string $description,
        $result = null
    ) : void {

        $response = [
            'status' => 200,
            'response' => 'successful',
            'description' => $description
        ];

        if ($result) $response = array_merge(
            $response,
            ['information' => Output::filter($result)]
        );
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
        header('Content-Type: application/json; charset=utf-8');
        http_response_code($code);
        die(json_encode($response));
    }
}
