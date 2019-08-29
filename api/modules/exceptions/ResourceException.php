<?php

namespace Modules\Exceptions;

use V2\Modules\JsonResponse;

class ResourceException extends JsonResponse
{
    private const HTTP_CODE = 404;

    public function __construct()
    {
        $response = [
            'status' => self::HTTP_CODE,
            'response' => 'error',
            'description' => '',
            'error' => [
                'code' => 0,
                'type' => 'resource exception',
                'message' => 'resource not found'
            ]
        ];

        self::send($response, self::HTTP_CODE);
    }
}
