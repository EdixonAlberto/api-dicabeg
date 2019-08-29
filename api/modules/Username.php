<?php

namespace V2\Modules;

use Modules\Tools\Code;
use V2\Database\Querys;
use V2\Modules\JsonResponse;

class Username
{
    public static function validate(string $name, bool $repeted = false)
    {
        $usernameRepeated = Querys::table('users')
            ->select('username')
            ->where('username', $name)
            ->get();

        if ($usernameRepeated != false) {
            $newName = substr($name, 0, (strpos($name, '-') > 0) ?
                strpos($name, '-') : strlen($name));

            $newName .= '-' . Code::create();

            self::validate($newName, true);
        } elseif ($repeted) JsonResponse::error([
            'message' => 'username exist',
            'suggested_username' => $name
        ], 400);

        else return $name;
    }
}
