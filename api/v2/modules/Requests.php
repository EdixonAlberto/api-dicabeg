<?php

namespace V2\Modules;

use V2\Modules\RequestManager;

class Requests extends RequestManager
{
    public $params;
    public $headers;
    public $body;

    public function __construct(object $params)
    {
        $this->params = self::getParams($params);
        $this->headers = self::getHeader();
        $this->body = self::getBody();
    }
}
