<?php

namespace V2\Modules;

use V2\Libraries\SendGrid;
use V2\Modules\EmailTemplate;

class Diffusion
{
    public static function sendEmail(
        string $email,
        EmailTemplate $template
    ) : int {
        $arrayStatus = SendGrid::generateEmail(
            $template::APP_EMAIL,
            $template->subject,
            $email,
            $template->html
        )->send();

        return $arrayStatus['statusCode'];
    }

    public function sendNotification()
    {

    }
}
