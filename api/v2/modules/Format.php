<?php

namespace V2\Modules;

use Exception;

class Format
{
    public static function email(string $email): string
    {
        if ($email) {
            $email = strtolower($email);
            $email = filter_var($email, FILTER_SANITIZE_STRING);
            $email = trim($email);
            $email = filter_var($email, FILTER_SANITIZE_EMAIL);
            $email = filter_var($email, FILTER_VALIDATE_EMAIL);
            $emailFormat = '/^\S+\@\S+\.\S+$/';

            if (preg_match($emailFormat, $email)) return $email;
            else throw new Exception('format the email incorrect', 400);
        } else throw new Exception('email is not set', 400);
    }

    public static function phone(string $phone): string
    {
        $phone = filter_var($phone, FILTER_SANITIZE_STRING);
        $phone = trim($phone);
        $phoneFormat = '/^[0-9]{3}\-[0-9]{3}\.[0-9]{2}\.[0-9]{2}$/';

        if (preg_match($phoneFormat, $phone)) return $phone;
        else throw new Exception('format the phone incorrect', 400);
    }

    public static function number(string $number)
    {
        if (is_numeric($number)) {
            $isFloatString = preg_match('/^\-?\d+\.\d+$/', $number);
            $number = $isFloatString ?
                (float) number_format($number, 5, '.', '') : (int) $number;
            return $number;
        } else throw new Exception(
            "the number: {$number} is incorrect," .
                'must have this format: 0.00000',
            400
        );
    }
}
