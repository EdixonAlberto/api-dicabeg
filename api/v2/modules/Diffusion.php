<?php

namespace V2\Modules;

use V2\Libraries\SendGrid;
use V2\Modules\EmailTemplate;

class Diffusion
{
    public function sendEmail(string $email, EmailTemplate $template)
    {
        echo 'Email enviado';
        return;

        return SendGrid::generateEmail(
            $template::APP_EMAIL,
            $template->subject,
            $email,
            $template->html
        )->send();
    }

    public function sendNotification()
    {

    }
}
