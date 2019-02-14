<?php

class JsonResponse
{
    public static function read($content, $resource)
    {
        $response = [
            $content => [
                'status' => 200,
                'response' => 'successful',
                'description' => 'found resource',
                'resource' => $resource
            ]
        ];
        self::send($response);
    }

    public static function created($content, $resource, $path = null, $info = null)
    {
        $response = [
            $content => [
                'status' => 201,
                'response' => 'successful',
                'description' => 'created resource',
                'resource' => $resource,
                'path' => $path,
                'information' => $info
            ]
        ];
        self::send($response, 201);
    }

    public static function updated($content, $resource)
    {
        $response = [
            $content => [
                'status' => 200,
                'response' => 'successful',
                'description' => 'updated resource',
                'resource' => $resource
            ]
        ];
        self::send($response);
    }

    public static function removed($content)
    {
        $response = [
            $content => [
                'status' => 200,
                'response' => 'successful',
                'description' => 'deleted resource',
            ]
        ];
        self::send($response);
    }

    public static function error($content, $description, $code)
    {
        $response = [
            $content => [
                'status' => $code,
                'response' => 'error',
                'description' => $description,
            ]
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
