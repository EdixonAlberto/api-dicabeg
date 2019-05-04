<?php

namespace V2\Libraries;

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

    public static function generateEmail($from, $subject, $to, $htmlContent)
    {
        $email = new Mail();
        $email->setFrom($from, 'DICABEG');  // Remitente
        $email->setSubject($subject);       // Asunto
        $email->addTo($to);                 // Para
        // $email->addContent("text/plain", "and easy to do anywhere, even with PHP"); //?
        $email->addContent(
            "text/html",
            $htmlContent                    // Plantilla html
        );
        $sendgrid = new \V2\Libraries\SendGrid;
        $sendgrid->email = $email;
        return $sendgrid;
    }

    public function send()
    {
        $sendgrid = new \SendGrid(getenv('SENDGRID_API_KEY'));
        $response = $sendgrid->send($this->email);

        $result = [
            'statusCode' => $response->statusCode(),
            'headers' => $response->headers(),
            'body' => $response->body()
        ];

        return $result;
    }
}
