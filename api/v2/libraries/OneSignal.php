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
        return $this->oneSignal;
    }

    public function addDevice()
    {
        $playerData = [
            'language' => 'en',
            'tags' => [
                'for' => 'bar',
                'this' => 'that'
            ]
        ];

        $player->create(DeviceTypes::CHROME_WEBSITE, $playerData);
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

    public function createNotifi($playerID)
    {
        $notification = new Notification(
            $this->oneSignal,
            ONESIGNAL_APP_ID,
            ONESIGNAL_REST_API_KEY
        );

        $notificationData = [
            // 'included_segments' => ['All'],
            'include_player_ids' => [$playerID],
            'contents' => [
                'en' => 'Hello, world',
            ],
            'headings' => [
                'en' => 'Hello',
            ],
            'buttons' => [
                [
                    'id' => 'button_id',
                    'text' => 'Button text',
                    'icon' => 'button_icon',
                ],
            ],
            'filters' => [
                [
                    'field' => 'tag',
                    'key' => 'level',
                    'relation' => '>',
                    'value' => '10',
                ],
            ],
            // 'send_after' => 'Sep 24 2017 14:00:00 GMT-0700',
            'isChromeWeb' => false,
        ];

        $notification->create($notificationData);
    }
}
