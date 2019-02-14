<?php

class JsonResponse
{
    public static function created($content, $path, $resource = null)
    {
        $response = [
            $content => [
                'status' => 200,
                'response' => 'successful',
                'description' => 'created resource',
                'resource' => $resource,
                'path' => $path
            ]
        ];
        self::send($response);
    }

    public static function updated($content)
    {
        $response = [
            $content => [
                'status' => 200,
                'response' => 'successful',
                'description' => 'updated resource'
            ]
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
