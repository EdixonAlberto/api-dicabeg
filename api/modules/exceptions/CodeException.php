<?php

namespace Modules\Exceptions;

class CodeException extends \UnexpectedValueException
{
    public function __construct(string $errType)
    {
        $this->message = 'code ' . $errType;
        $this->code = '401';
    }
}
