<?php

namespace V2\Controllers;

use Exception;
use V2\Modules\Time;
use V2\Modules\Format;
use V2\Database\Querys;
use V2\Modules\Security;
use V2\Modules\Diffusion;
use V2\Libraries\SendGrid;
use V2\Modules\Middleware;
use V2\Email\EmailTemplate;
use V2\Modules\JsonResponse;
use V2\Interfaces\IController;
use V2\Controllers\ReferredController;

class UserController implements IController
{
    public static function index() : void
    {
        $arrayUser = Querys::table('users')
            ->select(self::USERS_COLUMNS)
            ->group(GROUP_NRO)
            ->getAll(function () {
                throw new Exception('not found users', 404);
            });

        JsonResponse::read($arrayUser);
    }

    public static function show() : void
    {
        $user = Querys::table('users')
            ->select(self::USERS_COLUMNS)
            ->where('user_id', USERS_ID)
            ->get(function () {
                throw new Exception('user not found', 404);
            });

        JsonResponse::read($user);
    }

    public static function store($body) : void
    {
        Middleware::input($body);

        $userQuery = Querys::table('users');

        if (isset($body->email)) {
            $email = $userQuery->select('email')
                ->WHERE('email', $body->email)->get();
            if ($email) throw new Exception("email {{$email}} exist", 404);

        } else throw new Exception('email is not set', 400);


        if (isset($body->invite_code)) {
            $user_id = Querys::table('accounts')
                ->select('user_id')
                ->where('invite_code', $body->invite_code)
                ->get(function () {
                    throw new Exception('invite code incorrect', 400);
                });
            $username = Querys::table('users')
                ->select('username')
                ->where('user_id', $user_id)->get();
        }

        $invite_code = Security::generateCode(8);

        $userQuery->insert($user = (object)[
            'user_id' => $id = Security::generateID(),
            'email' => Format::email($body->email),
            'username' => self::getUsername($body),
            'password' => Security::generateHash($body->password),
            'invite_code' => $invite_code,
            'create_date' => Time::current($body->time_zone)->utc
        ])->execute();

        // ADD: Validar recurrencia de codigos,
        // asegurar unicidad con generacion recursiva del mismo
        $code = Security::generateCode(6);

        Querys::table('accounts')->insert([
            'user_id' => $id,
            'temporal_code' => $code,
            'invite_code' => $invite_code,
            'registration_code' => $username ?? null,
            'time_zone' => $body->time_zone
        ])->execute();

        // TODO: El idioma debe ser determinado en el
        // futuro mediante la config del usuario

        $info['email'] = Diffusion::sendEmail(
            $user->email,
            EmailTemplate::accountActivation($code, 'spanish')
        );

        if (isset($body->invite_code)) {
            ReferredController::store((object)[
                'user_id' => $user_id,
                'referred_id' => $id
            ]);
            $info['referred'] =
                "added as a referral from the user: {$username}";

            $player_id = Querys::table('users')
                ->select('player_id')
                ->where('username', $username)->get();

            // TODO: crear modelos de contenido para las notificaciones
            // ademas de tener el contenido en varios idiomas
            if ($player_id) {
                $info['notification'] = Diffusion::sendNotification(
                    $player_id,
                    "El usuario: {$user->username} se ha registrado como su referido"
                );
            }
        }

        $path = 'https://' . $_SERVER['SERVER_NAME'] . '/v2/accounts/activation';
        JsonResponse::created($user, $path, $info);
    }

    public static function update($body) : void
    {
        Middleware::input($body);

        $result = Querys::table('users')->update($user = (object)[
            'player_id' => $body->player_id ?? null,

            'username' => isset($body->username) ?
                self::getUsername($body) : null,

            'email' => isset($body->email) ?
                Format::email($body->email) : null,

            'password' => isset($body->password) ?
                Security::generateHash($body->password) : null,

            'names' => $body->names ?? null,
            'lastnames' => $body->lastnames ?? null,

            'age' => isset($body->age) ?
                Format::number($body->age) : null,

            'avatar' => $body->avatar ?? null,

            'phone' => isset($body->phone) ?
                Format::phone($body->phone) : null,

            'points' => isset($body->points) ?
                Format::number($body->points) : null,

            'money' => isset($body->money) ?
                Format::number($body->money) : null,

            'update_date' => Time::current()->utc
        ])->where('user_id', USERS_ID)
            ->execute();

        JsonResponse::updated($user);
    }

    public static function destroy() : void
    {
        Querys::table('history')->delete()
            ->where('user_id', USERS_ID)
            ->execute();

        Querys::table('referrals')->delete()
            ->where('user_id', USERS_ID)
            ->execute();

        Querys::table('accounts')->delete()
            ->where('user_id', USERS_ID)
            ->execute();

        Querys::table('users')->delete()
            ->where('user_id', USERS_ID)
            ->execute();

        JsonResponse::removed();
    }

    private static function getUsername($body) : string
    {
        if (isset($body->username)) {
            $username = Querys::table('users')
                ->select('username')
                ->where('username', $body->username)
                ->get();

            if ($username) self::sendUsername($username);
            else return $body->username;

        } else return substr($body->email, 0, strpos($body->email, '@'));
    }

    private static function sendUsername(string $username) : void
    {
        global $newUsername;

        $existUsername = Querys::table('users')
            ->select('username')
            ->where('username', $username)->get();

        if ($existUsername) {
            $newUsername = substr($username, 0, (strpos($username, '_') > 0) ?
                strpos($username, '_') : strlen($username));
            $newUsername .= '_' . Security::generateCode(4);

            self::sendUsername($newUsername);

        } else {
            JsonResponse::error([
                'message' => 'username exist',
                'suggested_username' => $newUsername
            ], 400);
        }
    }
}
