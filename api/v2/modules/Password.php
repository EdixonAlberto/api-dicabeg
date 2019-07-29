<?php

namespace Modules\Tools;

use Exception;
use V2\Database\Querys;

class Password
{
    private static $algo = PASSWORD_DEFAULT;
    private static $options = ['cost' => 11];
    private const MIN_LENGTH = 8;
    private const MAX_LENGTH = 15;

    public static function create(string $input): string
    {
        if ($input) {
            if (
                strlen($input) >= self::MIN_LENGTH
                and strlen($input) <= self::MAX_LENGTH
            ) return password_hash($input, self::$algo, self::$options);
            else throw new Exception(
                "{self::MIN_LENGTH} <= password <= {self::MAX_LENGTH}",
                400
            );
        } else throw new Exception('password is not set', 400);
    }

    public static function validate(string $input, object $user): bool
    {
        $correctPass = password_verify($input, $user->password);

        if ($correctPass) {
            $newPass = self::rehash($input);

            if ($newPass) {
                Querys::table('users')
                    ->update(['password' => $newPass])
                    ->where('user_id', $user->user_id)
                    ->execute();
            }
            return true;
        } else throw new Exception('passsword incorrect', 401);
    }

    private static function rehash(string $pass): string
    {
        $needsRehash = password_needs_rehash($pass, self::$algo, self::$options);
        return $needsRehash ? self::create($pass) : '';
    }
}
