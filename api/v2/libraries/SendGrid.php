<?php

namespace V2\Libraries;

use SendGrid\Mail\Mail;
use SendGrid as ApiSendGrid;

class SendGrid
{
    private $mail;
    private $userEmail;

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
            Plantilla html
        */
        $mail = new Mail();
        $mail->setFrom($from, 'DICABEG');
        $mail->setSubject($subject);
        $mail->addTo($to);
        $mail->addContent("text/html", $htmlContent);

        $sendgrid = new SendGrid;
        $sendgrid->mail = $mail;
        $sendgrid->userEmail = $to;
        return $sendgrid;
    }

    public function send(): object
    {
        $sendgrid = new ApiSendGrid(SENDGRID_API_KEY);
        $response = $sendgrid->send($this->mail);

        $code = ($response->statusCode() != 0) ?: 500;
        $resp = ($code == 202) ? "email sended" : 'email not sended';
        $description = $response->headers()[2] ?? null;

        $result = (object) [
            'status' => $code,
            'response' => $resp,
            'destiny_email' => $this->userEmail,
            'description' => $description
        ];
        return $result;
    }
}
