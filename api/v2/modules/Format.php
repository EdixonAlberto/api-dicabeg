<?php

namespace V2\Modules;

use Modules\Exceptions;

class Format
{
    private const DECIMALS = 5;
    private const REGEX = [
        'email' => '/^[a-z0-9._-]+\@[a-z]+\.(com|email)$/',
        'phone' => '/^[0-9]{3}\-[0-9]{3}\.[0-9]{2}\.[0-9]{2}$/',
        'float' => '/^\-?\d+\.\d+$/'
    ];

    public static function email(string $_email): string
    {
        if ($_email) {
            $email = filter_var($_email, FILTER_SANITIZE_STRING);
            $email = trim($email);
            $email = filter_var($email, FILTER_SANITIZE_EMAIL);
            $email = filter_var($email, FILTER_VALIDATE_EMAIL);

            if ($email) {
                $email = strtolower($email);
                if (preg_match(self::REGEX['email'], $email)) return $email;
            }
        } else new Exceptions\SetError('email');
        new Exceptions\ValidationError($_email, self::REGEX['email']);
    }

    public static function phone(string $_phone): string
    {
        $phone = filter_var($_phone, FILTER_SANITIZE_STRING);
        $phone = trim($phone);
        if ($phone) {
            if (preg_match(self::REGEX['phone'], $phone)) return $phone;
        }
        new Exceptions\ValidationError($_phone, self::REGEX['phone']);
    }

    public static function number(string $number)
    {
        if (is_numeric($number)) {
            $formatFloat = preg_match(self::REGEX['float'], $number);
            return $formatFloat ?
                (float) number_format($number, self::DECIMALS, '.', '') : (int) $number;
        } else new Exceptions\ValidationError($number, self::REGEX['float']);
    }
}
