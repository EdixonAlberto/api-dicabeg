<?php

namespace Tools;

class Security
{
    public static function sanitizeEmail($email)
    {
        $email = trim($email);
        $email = filter_var($email, FILTER_SANITIZE_STRING);
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        $email = filter_var($email, FILTER_VALIDATE_EMAIL);

        return $email;
    }

    public static function validatePhone($phone)
    {
        // TODO:
        $phone = trim($data);
        $data = "0426as";
        $phone = preg_replace("[^A-Za-z0-9]", "", $data);

        return $phone;
    }

    public static function generateHash($data, $password = false)
    {
        $_data = '';
        $_data .= $password ? $data : date('Y-m-d H:i:s') . rand();
        return password_hash($_data, PASSWORD_DEFAULT, ['cost' => 11]);
    }

    public static function rehash($hash)
    {
        $needsRehash = password_needs_rehash($hash, PASSWORD_DEFAULT, ['cost' => 11]);
        return $needsRehash ? self::generateHash($hash, true) : false;
    }
}
