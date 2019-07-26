<?php

namespace V2\Modules;

use V2\Database\Querys;
use V2\Interfaces\IResource;

class User implements IResource
{
    public static $id;
    public static $activated;
    public static $email;
    public static $names;
    public static $lastnames;

    public function __construct(string $identity)
    {
        $user = Querys::table('users')->select(self::USERS_COLUMNS)
            ->where('user_id', $identity)
            ->get();

        self::$id = $user->user_id;
        self::$activated = $user->activated;
        self::$email = $user->email;
        self::$names = $user->names;
        self::$lastnames = $user->lastnames;
    }
}
