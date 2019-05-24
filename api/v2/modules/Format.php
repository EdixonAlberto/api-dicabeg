<?php

namespace V2\Modules;

use Exception;

class Format
{
    public static function email(string $email) : string
    {
        $email = filter_var($email, FILTER_SANITIZE_STRING);
        $email = trim($email);
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        $email = filter_var($email, FILTER_VALIDATE_EMAIL);
        $emailFormat = '/^[a-z0-9_.]+\@[a-z]+\.com$/';

        if ($result = preg_match($emailFormat, $email)) return $email;
        else throw new Exception('format the email incorrect', 400);
    }

    public static function phone(string $phone) : string
    {
        $phone = filter_var($phone, FILTER_SANITIZE_STRING);
        $phone = trim($phone);
        $phoneFormat = '/^[0-9]{3}\-[0-9]{3}\.[0-9]{2}\.[0-9]{2}$/';

        if (preg_match($phoneFormat, $phone)) return $phone;
        else throw new Exception('format the phone incorrect', 400);
    }

    public static function number($number)
    {
        if (is_numeric($number)) {
            $isFloat = preg_match('|^\\d+\\.\\d+$|', $number);
            $number = $isFloat ?
                (float)number_format($number, 2, '.', '-') : (int)$number;
            return $number;

        } else throw new Exception("the number: {$number} is incorrect", 400);
    }
}
