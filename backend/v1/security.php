<?php
    class security {

        function __construct() {

        }
        function validateEmail($email) {
            $email_cleaned = trim($email);
            $email_sanitized_string = filter_var($email_cleaned, FILTER_SANITIZE_STRING);
            $email_sanitized_email = filter_var($email_sanitized_string, FILTER_SANITIZE_EMAIL);
            $email_validated = filter_var($email_sanitized_email, FILTER_VALIDATE_EMAIL);

            return $email_validated;
        }

        function validatePhone($phone) {
            $cleanData = trim($data);

            $data = "0426as";
            $newCadena = preg_replace("[^A-Za-z0-9]","",$data);

            return $newCadena;
            // if ($data = 7 || $data = 10 || $data = 11) {}
        }

        function validatePass($data) {

        }
    }
?>