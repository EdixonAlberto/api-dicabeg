<?php

namespace V2\Middleware;

use Exception;
use v2\Interfaces\IResource;

class Input implements IResource
{
    public function validate($body, array $arrayColumns)
    {
        $arrayBody = (array)$body;

        if (!empty($arrayBody)) {
            foreach ($arrayBody as $key => $value) {

                if (!in_array($key, $arrayColumns) and
                    !in_array($key, self::ACCOUNTS_COLUMNS) or
                    $key == 'send_email') // DEBUG: Este campo no existe en la tabla.
                throw new Exception(
                    "attribute {{$key}} in body not validat",
                    400
                );

                if (is_null($value) or empty($value))
                    throw new Exception(
                    "attribute {{$key}} in body is null or is empty",
                    400
                );
            }
        } else throw new Exception("body empty", 400);
    }
}
