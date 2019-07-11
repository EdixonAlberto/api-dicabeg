<?php

namespace V2\Controllers;

use Exception;
use V2\Modules\Time;
use V2\Modules\Format;
use V2\Database\Querys;
use V2\Middleware\Auth;
use V2\Modules\Security;
use V2\Modules\Username;
use V2\Modules\Diffusion;
use V2\Email\EmailTemplate;
use V2\Modules\JsonResponse;
use V2\Interfaces\IController;
use V2\Controllers\ReferredController;

class UserController implements IController
{
    public static function index($req) : void
    {
        $arrayUser = Querys::table('users')
            ->select(self::USERS_COLUMNS)
            ->group($req->params->nro)
            ->getAll(function () {
                throw new Exception('not found users', 404);
            });

        JsonResponse::read($arrayUser);
    }

    public static function show($req) : void
    {
        $user = Querys::table('users')
            ->select(self::USERS_COLUMNS)
            ->where('user_id', Auth::$id)
            ->get(function () {
                throw new Exception('user not found', 404);
            });

        JsonResponse::read($user);
    }

    public static function store($req) : void
    {
        $body = $req->body;

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
            if ($email) throw new Exception("email exist: {$email}", 404);

        } else throw new Exception('email is not set', 400);

        if (isset($body->invite_code)) {
            $user_id = Querys::table('users')
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

        $username = Username::validate($body->username);
        $id = Security::generateID();
        $code = Security::generateCode(6);
        Time::$timeZone = $body->time_zone;

        $userInsert = $userQuery->insert($newUser = (object)[
            'user_id' => $id,
            'username' => $username,
            'email' => Format::email($body->email),
            'invite_code' => Security::generateCode(8),
            'password' => Security::generateHash($body->password),
            'create_date' => Time::current()->utc
        ])->execute();

        $accountInsert = Querys::table('accounts')->insert([
            'user_id' => $id,
            'temporal_code' => $code,
            'referred_id' => $user->user_id ?? null,
            'time_zone' => $body->time_zone
        ])->execute();

        if (isset($body->invite_code)) {
            ReferredController::store((object)[
                'user_id' => $user->user_id,
                'referred_id' => $id
            ]);
            $info['as_referred'] = true;

            // ADD: crear modelos de contenido para las notificaciones
            // ademas de tener el contenido en varios idiomas
            // TODO: Probar notificaciones
            // if (isset($user->player_id) and $user->player_id != '') {
            //     $info['notification'] = Diffusion::sendNotification(
            //         [$user->player_id],
            //         "El usuario: {$username} se ha registrado como tu referido"
            //     );
            // }
        } else $info['as_referred'] = false;

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

    public static function update($req) : void
    {
        $body = $req->body;

        if (!$body) throw new Exception('body empty', 400);

        $id = Auth::$id;
        $userQuery = Querys::table('users');

        if (isset($body->balance)) {
            $currentBalance = Format::number($body->balance);

            $oldBalance = $userQuery->select('balance')
                ->where('user_id', $id)->get();

            $newBalance = $currentBalance + $oldBalance;

            $userQuery->update($user = (object)['balance' => $newBalance])
                ->where('user_id', $id)
                ->execute(function () {
                    throw new Exception('not updated balance', 500);
                });

        } else {
            $userQuery->update($user = (object)[
                'username' => isset($body->username) ?
                    Username::validate($body->username) : null,

                'names' => $body->names ?? null,
                'lastnames' => $body->lastnames ?? null,

                'age' => isset($body->age) ?
                    Format::number($body->age) : null,

                'avatar' => $body->avatar ?? null,

                'phone' => isset($body->phone) ?
                    Format::phone($body->phone) : null,

                'update_date' => Time::current()->utc

            ])->where('user_id', $id)
                ->execute(function () {
                    throw new Exception('not updated user', 500);
                });
        }

        JsonResponse::updated($user);
    }

    public static function destroy($req) : void
    {
        $id = Auth::$id;

        Querys::table('users')->select('user_id')
            ->where('user_id', $id)
            ->get(function () {
                throw new Exception('user not found', 404);
            });

        Querys::table('history')->delete()
            ->where('user_id', $id)
            ->execute();

        Querys::table('referreds')->delete()
            ->where('user_id', $id)
            ->execute();

        Querys::table('transfers')->delete()
            ->where('user_id', $id)
            ->execute();

        Querys::table('accounts')->delete()
            ->where('user_id', $id)
            ->execute();

        Querys::table('users')->delete()
            ->where('user_id', $id)
            ->execute();

        JsonResponse::removed();
    }
}
