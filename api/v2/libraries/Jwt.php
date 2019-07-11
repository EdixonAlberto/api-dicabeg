<?php

namespace V2\Libraries;

use V2\Modules\Time;
use Firebase\JWT\JWT as JwtToken;

class Jwt
{
    public $token;
    public $expiration_date;
    private const ALG = array('HS256');

    public function __construct(string $id, string $key)
    {
        /* token:
            sub: (sujeto) datos del usuario
            TODO: admin: , cliente: , establecimiento, etc
            iss: (emisor) autor o creador del token
            iat: (tiempo de creacion) en UNIX
            exp: (tiempo de expiracion) en UNIX */
        $token = array(
            'sub' => $id,
            'iss' => 'api-dicabeg',
            'iat' => Time::current()->unix,
            'exp' => Time::expiration()->unix,
        );

        $this->token = JwtToken::encode($token, $key);
        $this->expiration_date = $token['exp'];
    }

    public static function verific(string $token, string $key) : object
    {
        return JwtToken::decode($token, $key, self::ALG);
    }

    public static function extraTime(int $time_min) : void
    {
        JwtToken::$leeway = $time_min * 60;
    }
}
