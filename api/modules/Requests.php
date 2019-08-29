<?php

namespace V2\Modules;

use V2\Modules\RequestManager;

class Requests extends RequestManager
{
    public $params;
    public $headers;
    public $body;

    public function __construct()
    {
        $this->params = self::getParams();
        $this->headers = self::getHeader();
        $this->body = self::getBody();
    }
}
