<?php

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

    public static function encryptPassword()
    {
        // TODO: Seguir investigando sobre esto
        $password = $_REQUEST['password'];
        return password_hash($password, PASSWORD_DEFAULT);
    }

    public static function generateToken()
    {
        // TODO: Investigar sobre las tecnicas para crear token personalizado fuertes
        $token = 'SalchiPapa';
        return password_hash($token, PASSWORD_DEFAULT);
    }
}
