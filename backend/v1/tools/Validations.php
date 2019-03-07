<?php

namespace Tools;

class Validations
{
    public static function gui()
    {
        // No se valida si los parametros en GET estan seteados,
        // porque apache se asegura de esto con las redirecciones

        foreach ($_GET as $gui) {
            if ($gui == 'alls') continue;
            if (!preg_match(
                '/^([A-Z0-9]{8})((-)[A-Z0-9]{4}){3}(-)([A-Z0-9]{12})$/',
                $gui
            )) throw new \Exception('id incorrect', 400);
        }
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
        $phone = preg_replace('/[a-zA-Z\-\+\(\)]/', '', $phone);
        // Para dar formato: ([0-9]{3})(-)([0-9]{4})(-)([0-9]{4})
        return $phone;
    }
}
