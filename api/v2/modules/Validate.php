<?php

namespace V2\Modules;

class Validate
{
    public static function gui(string $gui)
    {
        if (!preg_match(
            '/^([A-Z0-9]{8})((-)[A-Z0-9]{4}){3}(-)([A-Z0-9]{12})$/',
            $gui
        )) return null;
        else throw new \Exception('id incorrect', 400);
    }

    public static function resource(string $resource, array $arrayResources)
    {
        $existResource = in_array($resource, $arrayResources);
        if ($existResource == false) throw new \Exception('not found resource', 404);
    }

    public static function token()
    {
        $token = $_SERVER['HTTP_API_TOKEN'] ?? false;
        if ($token == false) throw new \Exception('not found token', 404);
        else return $token;
    }

    public static function email()
    {
        $email = $_REQUEST['email'];
        $email = filter_var($email, FILTER_SANITIZE_STRING);
        $email = trim($email);
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        $email = filter_var($email, FILTER_VALIDATE_EMAIL);
        return $email;
    }

    public static function phone()
    {
        $phone = $_REQUEST['phone'];
        $phone = filter_var($phone, FILTER_SANITIZE_STRING);
        $phone = trim($phone);
        $phone = preg_replace('/^[a-zA-Z\-\+\(\)]$/', '', $phone); // FIXME:
        // Para dar formato: ([0-9]{3})(-)([0-9]{4})(-)([0-9]{4})
        return $phone;
    }
}
