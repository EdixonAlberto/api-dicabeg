<?php

namespace Modules\Exceptions;

use V2\Modules\JsonResponse;
use V2\Modules\InternalError;

class TokenError extends JsonResponse
{
    private const HTTP_CODE = 401;

    public function __construct()
    {
        $response = [
            'status' => self::HTTP_CODE,
            'response' => 'error',
            'description' => 'Expired Token', // TODO: colocar en minuscula despues
            'error' => new InternalError(
                71,
                'token',
                'expired token'
            )
        ];

        self::send($response, self::HTTP_CODE);
    }
}
