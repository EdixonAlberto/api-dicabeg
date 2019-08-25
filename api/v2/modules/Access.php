<?php

namespace V2\Modules;

use V2\Modules\Time;
use V2\Libraries\Jwt;
use V2\Database\Querys;

class Access
{
    public $data;

    public function __construct($id)
    {
        $sub = (object) [
            'id' => $id,
            'rol' => User::$rol ?? $this->getUserRol($id)
        ];

        $access = new Jwt($sub, ACCESS_KEY);

        $refresh = new Jwt($sub, REFRESH_KEY);

        $this->data = (object) [
            'access_token' => $access->token,
            'refresh_token' => $refresh->token,
            'expiration_date_unix' => $access->expiration_date,
            'expiration_date_utc' => date('Y-m-d H:i:s', $access->expiration_date),
            'time_zone' => Time::$timeZone,
            'expiration_time' => $this->expiration()
        ];
    }

    private function expiration(): string
    {
        // TODO: abstraer esto a class Time
        $time = Querys::table('options')
            ->select('expiration_time')
            ->get();
        $expiration_time = preg_replace('/( min)$/', '', $time);

        return $expiration_time;
    }

    private function getUserRol($id): int
    {
        $rol = Querys::table('users')
            ->select('rol_id')
            ->where('user_id', $id)
            ->get();

        return $rol;
    }
}
