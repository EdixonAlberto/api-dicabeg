<?php

namespace V2\Modules;

use V2\Libraries\SendGrid;
use V2\Libraries\OneSignal;
use V2\Modules\EmailTemplate;

class Diffusion
{
    private const HEADER = 'DICAPP';

    public static function sendEmail(
        string $email,
        EmailTemplate $template
    ) : object {

        $status = SendGrid::generateEmail(
            $template::SUPPORT_EMAIL,
            $template->subject,
            $email,
            $template->html
        )->send($email);
        return $status;
    }

    public function sendNotification(
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
