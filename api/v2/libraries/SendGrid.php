<?php

namespace V2\Libraries;

use SendGrid\Mail\Mail;
use SendGrid as ApiSendGrid;

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

    public static function generateEmail(
        $from,
        $subject,
        $to,
        $htmlContent
    ) : SendGrid {

        $email = new Mail();
        $email->setFrom($from, 'DICABEG');  // Remitente
        $email->setSubject($subject);       // Asunto
        $email->addTo($to);                 // Para
        // $email->addContent("text/plain", "and easy to do anywhere, even with PHP"); // TODO:?
        $email->addContent(
            "text/html",
            $htmlContent                    // Plantilla html
        );
        $sendgrid = new SendGrid;
        $sendgrid->email = $email;
        return $sendgrid;
    }

    public function send() : object
    {
        $sendgrid = new ApiSendGrid(SENDGRID_API_KEY);
        $response = $sendgrid->send($this->email);

        if ($response->statusCode() != 0) {
            $description = $response->headers()[2];

            $result = [
                'status' => $response->statusCode(),
                'response' => substr(
                    $description,
                    strrpos($description, ' ') + 1,
                    strlen($description)
                ),
                'description' => "email enviado al correo {{$this->email}}"
            ];

        } else {
            $result = [
                'status' => 500,
                'response' => 'error: email not sent',
                'description' => 'failed the connection to the internet'
            ];
        }

        return (object)$result;
    }
}
