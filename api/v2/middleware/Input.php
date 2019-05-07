<?php

namespace V2\Middleware;

use Exception;

class Input
{
    public function validate($body, array $arrayColumns)
    {
        $arrayBody = (array)$body;

        if (!empty($arrayBody)) {
            foreach ($arrayBody as $key => $value) {

                if (!in_array($key, $arrayColumns))
                    throw new Exception(
                    "attribute [{$key}] in body not validat",
                    400
                );

                if (is_null($value) or empty($value))
                    throw new Exception(
                    "attribute [{$key}] in body is null or is empty",
                    400
                );
            }
        } else throw new Exception("body empty", 400);
    }
}
