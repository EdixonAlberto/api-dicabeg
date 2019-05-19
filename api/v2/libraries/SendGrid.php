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

        $mail = new Mail();
        $mail->setFrom($from, 'DICABEG');  // Remitente
        $mail->setSubject($subject);       // Asunto
        $mail->addTo($to);                 // Para
        // $email->addContent("text/plain", "and easy to do anywhere, even with PHP"); // TODO:?
        $mail->addContent(
            "text/html",
            $htmlContent                    // Plantilla html
        );
        $sendgrid = new SendGrid;
        $sendgrid->mail = $mail;
        return $sendgrid;
    }

    public function send($email) : object
    {
        $sendgrid = new ApiSendGrid(SENDGRID_API_KEY);
        $response = $sendgrid->send($this->mail);

        if ($response->statusCode() != 0) {
            $description = $response->headers()[2];

            $result = [
                'status' => $response->statusCode(),
                'response' => substr(
                    $description,
                    strrpos($description, ' ') + 1,
                    strlen($description)
                ),
                'description' => "email sent to: {{$email}}"
            ];

        } else {
            $result = [
                'status' => 500,
                'response' => 'error',
                'description' => 'the email could not be sent'
            ];
        }
        return (object)$result;
    }
}
