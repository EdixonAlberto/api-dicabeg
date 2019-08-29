<?php

namespace V2\Modules;

class InternalError
{
    public $code, $type, $message, $data;

    public function __construct(int $code, string $type, string $message, array $data = null)
    {
        $this->code = $code;
        $this->type = $type;
        $this->message = $message;
        $this->data = $data;
    }
}
