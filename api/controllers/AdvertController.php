<?php

namespace V2\Controllers;

use Exception;
use V2\Modules\User;
use V2\Database\Querys;
use V2\Modules\JsonResponse;

class AdvertController
{
    public static function payBonus(): void
    {
        $userQuery = Querys::table('users');

        // TODO: abstraer esta funcionalidad a los anuncios de empresas
        // Comprobacion para el pago
        // self::checkoutVideo($body->video_id ?? null);

        $oldBalance = $userQuery->select('balance')
            ->where('user_id', User::$id)
            ->get();

        $pay = Querys::table('options')->select('pay_bonus')->get();

        $newBalance = $oldBalance + $pay;

        $userQuery->update($user = ['balance' => $newBalance])
            ->where('user_id', User::$id)
            ->execute(function () {
                throw new Exception('not updated balance', 500);
            });

        JsonResponse::updated($user);
    }

    private static function checkoutVideo(string $view_video_id = null): void
    {
        if ($view_video_id ?? null) {
            $save_video_id = Querys::table('history')->select('video')
                ->where('user_id', User::$id)
                ->get();

            $viewedVideo = $save_video_id === ($view_video_id ?? '');

            if ($viewedVideo) JsonResponse::OK('pago no procesado: video visto'); // TODO: repuesta en ingles
            // } else throw new Exception('attribute video_id is not set', 400);
        }
    }
}
