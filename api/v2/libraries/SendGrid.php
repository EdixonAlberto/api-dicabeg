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
        $to,
        $from,
        $subject,
        $htmlContent
    ): SendGrid {
        /*
            Remitente
            Asunto
            Para
            TODO:?
            Plantilla html
        */
        $mail = new Mail();
        $mail->setFrom($from, 'DICABEG');
        $mail->setSubject($subject);
        $mail->addTo($to);
        // $email->addContent("text/plain", "and easy to do anywhere, even with PHP");
        $mail->addContent(
            "text/html",
            $htmlContent
        );
        $sendgrid = new SendGrid;
        $sendgrid->mail = $mail;
        $sendgrid->email = $to;
        return $sendgrid;
    }

    public function send(): object
    {
        $sendgrid = new ApiSendGrid(SENDGRID_API_KEY);
        $response = $sendgrid->send($this->mail);

        if ($response->statusCode() == 201) {
            $description = $response->headers()[2];

            $result = (object) [
                'status' => '200',
                'response' => substr(
                    $description,
                    strrpos($description, ' ') + 1,
                    strlen($description)
                ),
                'description' => "email sent to: {$sendgrid->email}"
            ];
        } else {
            $result = (object) [
                'status' => 500,
                'response' => 'error: ' . $response->statusCode(),
                'description' => 'the email could not be sent'
            ];
        }
        return $result;
    }
}
