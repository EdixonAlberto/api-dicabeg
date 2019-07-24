<?php

namespace V2\Modules;

use V2\Modules\Time;
use V2\Libraries\Jwt;

class Access
{
    public $data;

    public function __construct($id)
    {
        $access = new Jwt($id, ACCESS_KEY);

        $refresh = new Jwt($id, REFRESH_KEY);

        $this->data = (object) [
            'access_token' => $access->token,
            'refresh_token' => $refresh->token,
            'expiration_date_unix' => $access->expiration_date,
            'expiration_date_utc' => date('Y-m-d H:i:s', $access->expiration_date),
            'time_zone' => Time::$timeZone
        ];
    }
}
