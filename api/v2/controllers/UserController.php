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
        if (!isset($body->password))
            throw new Exception('passsword is not set', 401);

        if (!isset($body->time_zone))
            throw new Exception('time_zone is not set', 400);

        if (!isset($body->send_email))
            throw new Exception('send_email is not set', 400);

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

            $user = $userQuery->select([
                'user_id',
                'player_id',
                'username'
            ])->where('user_id', $user_id)->get();
        }

        $username = self::getUsername($body);
        $id = Security::generateID();
        $invite_code = Security::generateCode(8);
        // ADD: Validar recurrencia de codigos,
        // asegurar unicidad con generacion recursiva del mismo
        $code = Security::generateCode(6);
        $info = null;

        $userInsert = $userQuery->insert($newUser = (object)[
            'user_id' => $id,
            'username' => $username,
            'email' => Format::email($body->email),
            'password' => Security::generateHash($body->password),
            'invite_code' => $invite_code,
            'create_date' => Time::current($body->time_zone)->utc
        ])->execute();

        $accountInsert = Querys::table('accounts')->insert([
            'user_id' => $id,
            'temporal_code' => $code,
            'invite_code' => $invite_code,
            'registration_code' => $user->username ?? null,
            'time_zone' => $body->time_zone
        ])->execute();

        if (isset($body->invite_code)) {
            ReferredController::store((object)[
                'user_id' => $user->user_id,
                'referred_id' => $id,
                'time_zone' => $body->time_zone
            ]);
            $info['as_referred'] = $username;

            // ADD: crear modelos de contenido para las notificaciones
            // ademas de tener el contenido en varios idiomas
            if (isset($user->player_id) and $user->player_id != '') {
                $info['notification'] = Diffusion::sendNotification(
                    [$user->player_id],
                    "El usuario: {$username} se ha registrado como tu referido"
                );
            }
        }

        if ($body->send_email == 'true') {
            $info['email'] = Diffusion::sendEmail(
                $newUser->email,
                    // ADD: El idioma debe ser determinado en el
                    // futuro mediante la config del usuario
                EmailTemplate::accountActivation($code)
            );

        } elseif ($body->send_email == 'false') {
            $info['email'] = [
                'response' => 'not sended',
                'temporal_code' => $code
            ];
        }

        $path = 'https://' . $_SERVER['SERVER_NAME'] . '/v2/accounts/activation';
        JsonResponse::created($newUser, $path, $info);
    }

    public static function update($body) : void
    {
        if (!$body) throw new Exception('body empty', 400);

        $userQuery = Querys::table('users');

        if (isset($body->balance)) {
            Format::number($newBalance = $body->balance);

            $currentBalance = $userQuery->select('balance')
                ->where('user_id', USERS_ID)->get();

            $balance = $currentBalance + $newBalance;

            $userQuery->update($user = (object)['balance' => $balance])
                ->where('user_id', USERS_ID)
                ->execute(function () {
                    throw new Exception('not updated balance', 500);
                });

        } else {
            $userQuery->update($user = (object)[
                'player_id' => $body->player_id ?? null,

                'username' => isset($body->username) ?
                    self::getUsername($body) : null,

                'names' => $body->names ?? null,
                'lastnames' => $body->lastnames ?? null,

                'age' => isset($body->age) ?
                    Format::number($body->age) : null,

                'avatar' => $body->avatar ?? null,

                'phone' => isset($body->phone) ?
                    Format::phone($body->phone) : null,

                'update_date' => Time::current()->utc

            ])->where('user_id', USERS_ID)
                ->execute(function () {
                    throw new Exception('not updated user', 500);
                });
        }

        JsonResponse::updated($user);
    }

    public static function config($body)
    {
        if (isset($body->email)) {

            $var = Querys::table('users')->select('username')
                ->where('email', $body->email)
                ->get(function () {
                    throw new Exception('email not exist', 400);
                });

            var_dump($var);
            die;

            $code = Security::generateCode(6);

            $emailResp = Diffusion::sendEmail(
                $body->email,
                EmailTemplate::update()
            );

            if ($emailResp->status == 500) throw new Exception($emailResp, 500);

        } elseif (isset($body->password)) {


        } else throw new Exception('no set parameter', 404);

    }

    public static function destroy() : void
    {
        Querys::table('history')->delete()
            ->where('user_id', USERS_ID)
            ->execute();

        Querys::table('referrals')->delete()
            ->where('user_id', USERS_ID)
            ->execute();

        Querys::table('transfers')->delete()
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
