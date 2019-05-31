<?php

namespace V2\Libraries;

use NNV\OneSignal\API\Player;
use NNV\OneSignal\OneSignal as OS;
use NNV\OneSignal\API\Notification;

class OneSignal
{
    private $oneSignal;

    public function __construct()
    {
        $this->oneSignal = new OS(
            ONESIGNAL_USER_AUTH_KEY,
            ONESIGNAL_APP_ID,
            ONESIGNAL_REST_API_KEY
        );
    }

    public static function addDevice()
    {
        $playerData = [
            'language' => 'es',
            'tags' => [
                'for' => 'bar',
                'this' => 'that'
            ]
        ];
        // $player->create(DeviceTypes::CHROME_WEBSITE, $playerData);
    }

    public function viewDevices()
    {
        $player = new Player(
            $this->oneSignal,
            ONESIGNAL_APP_ID,
            ONESIGNAL_REST_API_KEY
        );
        return $player->all();
    }

    public function createNotification(
        array $arrayPlayerId,
        string $header,
        string $content
    ) : array {

        $notification = new Notification(
            $this->oneSignal,
            ONESIGNAL_APP_ID,
            ONESIGNAL_REST_API_KEY
        );

        $notificationData = [
            'include_player_ids' => $arrayPlayerId,

            'headings' => [
                'es' => $header,
            ],

            'contents' => [
                'en' => $content
            ],

            'buttons' => [
                [
                    'id' => 'button_id',
                    'text' => 'Button text',
                    'icon' => 'button_icon',
                ],
            ],
        ];

        $response = $notification
            ->create($notificationData)
            ->response;

        if (!isset($response->errors)) {
            $result = [
                'status' => 200,
                'response' => 'successful',
                'description' =>
                    "notification: {$response->id} sended"
            ];

        } elseif (!isset($response->errors)) {
            $result = [
                'status' => 500,
                'response' => 'error',
                'description' => $response->warnings
            ];

        } else {
            $result = [
                'status' => 500,
                'response' => 'error',
                'description' => 'unknown error'
            ];
        }
        return $result;
    }
}
