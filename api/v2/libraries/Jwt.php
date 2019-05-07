<?php

namespace V2\Libraries;

use V2\Modules\Time;
use Firebase\JWT\JWT as JwtToken;

class Jwt
{
    private static $alg = array('HS256');

    public function __construct(string $data)
    {
        $token = array(
            'sub' => $data,                     // sujeto: datos del usuario
                                                // TODO: admin: , cliente: , establecimiento, etc
            'iss' => 'api-dicabeg',             // emisor: autor o creador del token
            'iat' => Time::current()->unix,     // tiempo de creacion en UNIX
            'exp' => Time::expiration()->unix   // tiempo de expiracion en UNIX
        );

        $this->api_token = JwtToken::encode($token, SECRET_KEY);
        $this->expiration_time = $token['exp'];

        return $this;
    }

    public static function process(string $token)
    {
        return JwtToken::decode($token, SECRET_KEY, self::$alg);
    }
}
