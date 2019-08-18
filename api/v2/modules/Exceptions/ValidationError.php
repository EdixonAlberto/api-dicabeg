<?php

namespace Modules\Exceptions;

use V2\Modules\JsonResponse;
use V2\Modules\InternalError;

class ValidationError extends JsonResponse
{
    private const HTTP_CODE = 400;

    public function __construct(string $data, string $regex)
    {
        $response = [
            'status' => self::HTTP_CODE,
            'response' => 'error',
            'description' => 'validation error',
            'error' => new InternalError(
                30,
                'format',
                "data: {$data} is incorrect",
                ['format_regex' => $regex]
            )
        ];

        self::send($response, self::HTTP_CODE);
    }
}
