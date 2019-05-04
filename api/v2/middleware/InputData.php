<?php

namespace V2\Middleware;

use Exception;
use V2\Interfaces\IResource;

class InputData implements IResource
{
    public function __callStatic($name, $request)
    {
        $arrayBody = (array)$request[0]->body;

        if (!empty($arrayBody)) {
            foreach ($arrayBody as $key => $value) {
                if (!in_array($key, self::$request->resource . _PASS_COLUMNS)
                    and !in_array($key, self::CODES_PASS_COLUMNS))
                    throw new Exception(
                    "attribute [{$key}] not validat",
                    400
                );

                if (is_null($value) or empty($value))
                    throw new Exception(
                    "attribute [{$key}] is null or is empty",
                    400
                );
            }
        } else throw new Exception("body empty", 400);
    }
}
