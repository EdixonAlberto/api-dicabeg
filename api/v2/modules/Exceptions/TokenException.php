<?php

namespace Modules\Exceptions;

use V2\Modules\JsonResponse;

class TokenException extends JsonResponse
{
    private const TYPE = 'token exception';
    private const HTTP_CODE = 401;

    // Codigo interno de error para la autenticacion: 7x
    public function __construct($err = null)
    {
        switch ($err) {
            case 'Expired Token':
                $error = [
                    'code' => 71,
                    'type' => self::TYPE,
                    'message' => 'expired token'
                ];
                break;

            case 'Wrong number of segments':
                $error = [
                    'code' => 72,
                    'type' => self::TYPE,
                    'message' => 'incorrect token length'
                ];
                break;

            case '':
                $error = [
                    'code' => 73,
                    'type' => self::TYPE,
                    'message' => ''
                ];
                break;

            default:
                $error = [
                    'code' => 0,
                    'type' => self::TYPE,
                    'message' => 'error undefined'
                ];
                break;
        }

        $response = [
            'status' => self::HTTP_CODE,
            'response' => 'error',
            'description' => 'Expired Token', // TODO: msj provicional, mientras se actualiza el front
            'error' => $error
        ];

        self::send($response, self::HTTP_CODE);
    }
}
