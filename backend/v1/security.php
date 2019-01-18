<?php

class security {

    function cleanEmail($email) {
        $email = trim($email);
        $email= filter_var($email, FILTER_SANITIZE_STRING);
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        $email = filter_var($email, FILTER_VALIDATE_EMAIL);

        return $email;
    }

    function cleanPhone($data) {
        $data = trim($data);
        $data = preg_match('/^((1-)?\d{3})-\d{3}-\d{4}/', $data);

        return $data;
    }

    function encryptPassword($data) {
        $data = password_hash($data, PASSWORD_DEFAULT); // La seguridad se manteniene actualizada

        return $data;
    }
}
?>