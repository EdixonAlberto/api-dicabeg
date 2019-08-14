<?php

namespace V2\Modules;

use V2\Database\Querys;
use V2\Interfaces\IResource;

class User implements IResource
{
    public static $id;
    public static $activated;
    public static $username;
    public static $email;
    public static $balance;
    public static $names;
    public static $lastnames;
    public static $age;
    public static $avatar;
    public static $phone;
    public static $player_id;
    public static $invite_code;
    public static $password;
    public static $create_date;
    public static $update_date;

    public function __construct(string $identity)
    {
        $user = Querys::table('users')->select(self::USERS_COLUMNS)
            ->where('user_id', $identity)
            ->get();

        self::$id = $user->user_id;
        self::$activated = $user->activated;
        self::$username = $user->username;
        self::$email = $user->email;
        self::$balance = $user->balance;
        self::$names = $user->names;
        self::$lastnames = $user->lastnames;
        self::$age = $user->age;
        self::$avatar = $user->avatar;
        self::$phone = $user->phone;
        self::$player_id = $user->player_id;
        self::$invite_code = $user->invite_code;
        self::$password = $user->password;
        self::$create_date = $user->create_date;
        self::$update_date = $user->update_date;
    }
}
