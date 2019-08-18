<?php

namespace Modules\Exceptions;

use V2\Modules\JsonResponse;
use V2\Modules\InternalError;

class SetError extends JsonResponse
{
    private const HTTP_CODE = 400;

    public function __construct(string $data)
    {
        $response = [
            'status' => self::HTTP_CODE,
            'response' => 'error',
            'description' => 'attibute error',
            'error' => new InternalError(
                50,
                'attibute',
                "attibute {$data} is not set"
            )
        ];

        self::send($response, self::HTTP_CODE);
    }
}
