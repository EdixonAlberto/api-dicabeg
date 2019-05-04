<?php

namespace V2\Libraries;

use V2\Modules\Time;
use Firebase\JWT\JWT as JwtToken;

class Jwt
{
    private static $alg = array('HS256');

    public static function create(string $data)
    {
        $token = array(
            'sub' => $data,                     // sujeto: datos del usuario
                                                // TODO: admin: , cliente: , establecimiento, etc
            'iss' => 'api-dicabeg',             // emisor: autor o creador del token
            'iat' => Time::current()->unix,     // tiempo de creacion en UNIX
            'exp' => Time::expiration()->unix   // tiempo de expiracion en UNIX
        );
        return JwtToken::encode($token, SECRET_KEY);
    }

    public static function process(string $token)
    {
        return JwtToken::decode($token, SECRET_KEY, self::$alg);
    }
}
