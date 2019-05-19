<?php

namespace V2\Modules;

use V2\Libraries\SendGrid;
use V2\Email\EmailTemplate;
use V2\Libraries\OneSignal;

class Diffusion
{
    public static function sendEmail(
        string $email,
        EmailTemplate $template
    ) : object {

        $status = SendGrid::generateEmail(
            $template::APP_EMAIL,
            $template->subject,
            $email,
            $template->html
        )->send($email);
        return $status;
    }

    public function sendNotification(
        string $playerId,
        string $content
    ) {

        $os = new OneSignal;

        $status = $os->createNotific(
            $playerId,
            $content
        );
        return $status;
    }
}
