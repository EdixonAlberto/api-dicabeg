<?php

namespace V2\Modules;

use V2\Libraries\SendGrid;
use V2\Libraries\OneSignal;

class Diffusion
{
    private const HEADER = 'DICAPP';

    public static function sendEmail(
        string $send,
        string $email,
        callable $functionBuildTemplate
    ): object {
        if (isset($send)) {
            if ($send == 'true') {
                $template = $functionBuildTemplate;

                $response = SendGrid::generateEmail(
                    $email,
                    $template::SUPPORT_EMAIL,
                    $template::$subject,
                    $template->html
                )->send();
            } elseif ($send == 'false') {
                $response = (object) [
                    'status' => 200,
                    'response' => 'not sended'
                ];
            } else throw new Exception(
                "the send_email field should be: 'true' or 'false'",
                400
            );
        } else throw new Exception("send_email is not set", 400);

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
