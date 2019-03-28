<?php

namespace V2\Libraries;

use \Firebase\JWT\JWT;

class PhpJwt
{

    public static function create()
    {
        $key = getenv('SECRECT_KEY');

        $token = array(
            'iss' => $iss,
            'aud' => $aud,
            'iat' => $iat,
            'nbf' => $nbf
        );

        return JWT::encode($token, $key);
    }

    public static function decode()
    {
        $decoded = JWT::decode($jwt, $key, array('HS256'));
    }



/*
     * You can add a leeway to account for when there is a clock skew times between
     * the signing and verifying servers. It is recommended that this leeway should
     * not be bigger than a few minutes.
     *
     * Source: http://self-issued.info/docs/draft-ietf-oauth-json-web-token.html#nbfDef
     */
    // JWT::$leeway = 60; // $leeway in seconds
    // $decoded = JWT::decode($jwt, $key, array('HS256'));

}

// $token = array(
//     "iss" => "example.org",
//     "aud" => "example.com",
//     "iat" => 1356999524,
//     "nbf" => 1357000000
// );

// $jwt = JWT::encode($token, $privateKey, 'RS256');
// $encode = "Encode:\n" . print_r($jwt, true) . "\n\n";

// $decoded = JWT::decode($jwt, $publicKey, array('RS256'));

// /*
//  NOTE: This will now be an object instead of an associative array. To get
//  an associative array, you will need to cast it as such:
//  */

// $decoded_array = (array)$decoded;
// $decode = "Decode:\n" . print_r($decoded_array, true) . "\n";

// var_dump($encode, $decode);
