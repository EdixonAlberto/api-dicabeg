<?php

namespace Tools;

class JsonResponse
{
    public static function read($content, $resource)
    {
        $response = [
            'status' => 200,
            'response' => 'successful',
            'description' => 'found resource',
            'resource' => [
                $content => $resource
            ]
        ];
        self::send($response);
    }

    public static function created($content, $resource, $path = null, $info = null)
    {
        $response = [
            'status' => 201,
            'response' => 'successful',
            'description' => 'created resource',
            'resource' => [
                $content => $resource
            ],
            'path' => $path,
            'information' => $info
        ];
        self::send($response, 201);
    }

    public static function updated($content, $resource, $info = null)
    {
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

    public static function error($description, $code)
    {
        $response = [
            'status' => $code,
            'response' => 'error',
            'description' => $description,
        ];
        self::send($response, $code);
    }

    public static function send($response, $code = 200)
    {
        header("Content-Type: application/json; charset=UTF-8");
        http_response_code($code);
        echo json_encode($response);
    }
}
