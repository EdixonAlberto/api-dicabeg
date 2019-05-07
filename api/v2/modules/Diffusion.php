<?php

namespace V2\Modules;

use V2\Libraries\SendGrid;
use V2\Email\EmailTemplate;

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
        )->send();

        return $status;
    }

    public function sendNotification()
    {

    }
}
