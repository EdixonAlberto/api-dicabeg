<?php

namespace Lib;

use SendGrid\Mail\Mail;

class SendGrid
{
   // require 'vendor/autoload.php'; // If you're using Composer (recommended)
   // Comment out the above line if not using Composer
   // require("<PATH TO>/sendgrid-php.php");
   // If not using Composer, uncomment the above line and
   // download sendgrid-php.zip from the latest release here,
   // replacing <PATH TO> with the path to the sendgrid-php.php file,
   // which is included in the download:
   // https://github.com/sendgrid/sendgrid-php/releases

    protected static function generateEmail($from, $to, $subject, $contentHTML)
    {
        $email = new Mail();
        $email->setFrom($from, 'DICABEG'); // Remitente
        $email->setSubject($subject); // Asunto
        $email->addTo($to); // Para
        $email->addContent("text/plain", "and easy to do anywhere, even with PHP"); //?
        $email->addContent( // Plantilla
            "text/html",
            $contentHTML // ejemp: "<strong>and easy to do anywhere, even with PHP</strong>"
        );
        $sendgrid = new \SendGrid(getenv('SENDGRID_API_KEY'));
        try {
            $response = $sendgrid->send($email);
            print $response->statusCode() . "\n";
            print_r($response->headers());
            print $response->body() . "\n";
        } catch (\Exception $e) {
            echo 'Caught exception: ' . $e->getMessage() . "\n";
        }
    }
}
