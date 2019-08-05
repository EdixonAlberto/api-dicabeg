<?php

namespace Modules\Exceptions;

use V2\Modules\JsonResponse;

class ExpiredException extends JsonResponse
{
    public function __construct()
    {
        $httpCode = 401;

        $response = [
            'status' => $httpCode,
            'response' => 'error',
            'description' => 'Expired Token', // TODO: msj provicional, mientras se actualiza el front
            'error' => [
                'code' => 71,
                'type' => 'token exception',
                'message' => 'expired token'
            ]
        ];

        self::send($response, $httpCode);
    }
}
