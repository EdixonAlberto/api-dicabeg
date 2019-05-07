<?php

namespace V2\Modules;

class Format
{
    public static function email(string $email)
    {
        $email = filter_var($email, FILTER_SANITIZE_STRING);
        $email = trim($email);
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        $email = filter_var($email, FILTER_VALIDATE_EMAIL);

        if ($email == false) throw new Exception('email incorrect', 400);
        else return $email;
    }

    public static function phone(string $phone)
    {
        $phone = filter_var($phone, FILTER_SANITIZE_STRING);
        $phone = trim($phone);
        $arraySimbols = ['-', '+', '/', '(', ')', '.'];
        foreach ($arraySimbols as $simbol) {
            $phone = preg_replace("/\\{$simbol}/", '', $phone); // TODO: usar expresiones regulares
        }
        // TODO: Para dar formato: +([0-9]{?})([0-9]{3})-([0-9]{4})-([0-9]{4})
        return $phone;
    }
}
