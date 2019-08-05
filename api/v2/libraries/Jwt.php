<?php

namespace V2\Libraries;

use Exception;
use V2\Modules\Time;
use Firebase\JWT\JWT as JwtToken;
use Modules\Exceptions\ExpiredException;

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

    public static function verific(string $token, string $key): object
    {
        try {
            return JwtToken::decode($token, $key, self::ALG);
        } catch (Exception $err) {
            $message = $err->getMessage();

            if ($message == 'Expired token') new ExpiredException;
            else throw new Exception($message, $err->getCode());
        }
    }

    public static function extraTime(int $time_min): void
    {
        JwtToken::$leeway = $time_min * 60;
    }
}
