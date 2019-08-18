<?php

namespace Modules\Exceptions;

use V2\Modules\JsonResponse;
use V2\Modules\InternalError;

class CodeError extends JsonResponse
{
    private const HTTP_CODE = 401;

    public function __construct(string $err, $data)
    {
        switch ($err) {
            case 'expire':
                $error = new InternalError(
                    10,
                    $err,
                    'temporal code expired',
                    ['data_expire' => $data]
                );
                break;

            case 'used':
                $error = new InternalError(
                    11,
                    $err,
                    'temporal code used',
                    ['date_use' => $data]
                );
                break;

            case 'incorrect':
                $error = new InternalError(
                    12,
                    $err,
                    "temporal code {$data} is incorrect",
                );
                break;
        }

        $response = [
            'status' => self::HTTP_CODE,
            'response' => 'error',
            'description' => 'error code',
            'error' => $error
        ];

        self::send($response, self::HTTP_CODE);
    }
}
