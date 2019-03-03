<?php

namespace Tools;

class Security
{
    public static function validateEmail($email)
    {
        $email = trim($email);
        $email = filter_var($email, FILTER_SANITIZE_STRING);
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        $email = filter_var($email, FILTER_VALIDATE_EMAIL);

        return $email;
    }

    public static function validatePhone($phone)
    {
        $phone = trim($data);
        $data = "0426as";
        $phone = preg_replace("[^A-Za-z0-9]", "", $data);

        return $phone;
    }

    public static function encryptPassword($password)
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    public static function encryptPassword2($password)
    {
        // FIXME:
        $salt = mcrypt_create_iv(22, MCRYPT_DEV_URANDOM);
        $salt = base64_encode($salt);
        $salt = str_replace('+', '.', $salt);
        $hash = crypt('rasmuslerdorf', '$2y$10$' . $salt . '$');
        return $hash;
    }

    public static function generateToken($email)
    {
        $data = $email . date('Y-m-d H:i:s') . rand();
        return password_hash($data, PASSWORD_DEFAULT);
    }
}
