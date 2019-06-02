<?php

namespace V2\Libraries;

use V2\Modules\Time;
use Firebase\JWT\JWT as JwtToken;

class Jwt
{
    public $token;
    public $expiration_date;
    private const ALG = array('HS256');

    public function __construct(string $data, string $key = ACCESS_KEY)
    {
        $token = array(
            'sub' => $data,                     // sujeto: datos del usuario
                                                // TODO: admin: , cliente: , establecimiento, etc
            'iss' => 'api-dicabeg',             // emisor: autor o creador del token
            'iat' => Time::current()->unix,     // tiempo de creacion en UNIX
            'exp' => Time::expiration()->unix   // tiempo de expiracion en UNIX
        );

        $this->token = JwtToken::encode($token, $key);
        $this->expiration_date = $token['exp'];
    }

    public static function verific(string $token, string $key) : object
    {
        return JwtToken::decode($token, $key, self::ALG);
    }
}
