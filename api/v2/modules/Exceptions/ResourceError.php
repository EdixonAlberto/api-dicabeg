<?php

namespace Modules\Exceptions;

use V2\Modules\JsonResponse;
use V2\Modules\InternalError;

class ResourceError extends JsonResponse
{
    private const HTTP_CODE = 404;

    public function __construct(string $data)
    {
        $response = [
            'status' => self::HTTP_CODE,
            'response' => 'error',
            'description' => 'resource error',
            'error' => new InternalError(
                44,
                'resource',
                "resource {$data} not found"
            )
        ];

        self::send($response, self::HTTP_CODE);
    }
}
