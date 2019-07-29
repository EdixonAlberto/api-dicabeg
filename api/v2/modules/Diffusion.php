<?php

namespace V2\Modules;

use V2\Libraries\SendGrid;
use V2\Libraries\OneSignal;
use V2\Modules\EmailTemplate;

class Diffusion
{
    private const HEADER = 'DICAPP';

    public static function sendEmail(
        string $send,
        string $email,
        EmailTemplate $template
    ): object {
        if ($send == 'true') {
            $response = SendGrid::generateEmail(
                $email,
                $template::SUPPORT_EMAIL,
                $template::$subject,
                $template->html
            )->send();
        } elseif ($send == 'false') {
            $response = (object) [
                'response' => 'email not sended',
                'destiny_email' => $email,
                'temporal_code' => $template->code
            ];
        } else throw new Exception(
            "the send_email field should be: 'true' or 'false'",
            400
        );
        return $response;
    }

    public static function sendNotification(
        array $arrayPlayerId,
        string $content
    ) {
        $os = new OneSignal;
        $status = $os->createNotification(
            $arrayPlayerId,
            self::HEADER,
            $content
        );
        return $status;
    }
}
