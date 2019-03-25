<?php

namespace V2\Routes;

class Config
{
    protected $arrayRequest;
    protected $arrayResource;

    public function __construct()
    {
        $this->arrayRequest = [
            '/^\/([a-z]+)$/', // /route1
            '/^\/([a-z]+)\/([A-Z0-9-]{36})$/', // route1/id1
            '/^\/([a-z]+)\/([A-Z0-9-]{36})\/([a-z]+)$/', // route1/id1/route2
            '/^\/([a-z]+)\/([A-Z0-9-]{36})\/([a-z]+)\/([A-Z0-9-]{36})$/' // /route1/id1/route2/id2
        ];

        $this->arrayResource = [
            'users',
            'history',
            'referrals',
            'sessions',
            'videos'
        ];
    }
}
