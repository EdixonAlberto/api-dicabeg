<?php

namespace V2\Controllers;

use Exception;
use V2\Modules\Time;
use V2\Modules\User;
use V2\Modules\Format;
use Modules\Tools\Code;
use V2\Database\Querys;
use V2\Modules\Security;
use V2\Modules\Username;
use V2\Modules\Diffusion;
use Modules\Tools\Password;
use V2\Modules\JsonResponse;
use V2\Modules\EmailTemplate;
use V2\Interfaces\IController;
use V2\Controllers\AdvertController;
use V2\Controllers\ReferredController;

class UserController implements IController
{
    public static function index($req): void
    {
        $arrayUser = Querys::table('users')
            ->select(self::USERS_COLUMNS)
            ->group($req->params->nro, $req->params->order)
            ->getAll(function () {
                throw new Exception('not found users', 404);
            });

        JsonResponse::OK('list of users', $arrayUser);
    }

    public static function show($req): void
    {
        $user = Querys::table('users')
            ->select(self::USERS_COLUMNS)
            ->where('user_id', User::$id)
            ->get();

        JsonResponse::read($user);
    }

    public static function store($req): void
    {
        $body = $req->body;

        if (isset($body->time_zone) == false)
            throw new Exception('time_zone is not set', 400);

        if (isset($body->send_email) == false)
            throw new Exception('send_email is not set', 400);

        $userQuery = Querys::table('users');

        if (isset($body->email)) {
            $emailFound = $userQuery->select('email')
                ->WHERE('email', $body->email)
                ->get();

            if ($emailFound) throw new Exception(
                "email exist: {$emailFound}",
                400
            );
        } else throw new Exception('email is not set', 400);

        // Guardando los datos del referido
        $referred = isset($body->invite_code) ? $userQuery
            ->select(['user_id',  'player_id', 'username'])
            ->where('invite_code', $body->invite_code)
            ->get(function () {
                throw new Exception('invite code incorrect', 400);
            }) : null;

        $pass = Password::create($body->password ?? '');
        $username = Username::validate($body->username);
        $email =  Format::email($body->email);
        $id = Security::generateID();
        $code = Code::create();

        $userQuery->insert($newUser = (object) [
            'user_id' => $id,
            'username' => $username,
            'email' => $email,
            'invite_code' => Code::create(),
            'password' => $pass,
            'create_date' => Time::current($body->time_zone)->utc
        ])->execute();

        Querys::table('accounts')->insert([
            'email' => $email,
            'temporal_code' => $code,
            'last_email_sended' => 'accountActivation',
            'referred_id' => $referred->user_id ?? null,
            'time_zone' => $body->time_zone,
            'code_create_date' => Time::current()->utc
        ])->execute();

        // Creando referido
        // Nota: es necesario intercambiar los id
        if (isset($body->invite_code)) {
            ReferredController::store((object) [
                'user_id' => $referred->user_id,
                'referred_id' => $id
            ]);

            $info['referred'] = $referred->user_id;

            // ADD: crear modelos de contenido para las notificaciones
            // ademas de tener el contenido en varios idiomas

            // TODO: Apagando notificaciones. Reparar despues
            // if (isset($user->player_id) and $user->player_id != '') {
            //     $info['notification'] = Diffusion::sendNotification(
            //         [$user->player_id],
            //         "El usuario: {$username} se ha registrado como tu referido"
            //     );
            // }
        }

        $info['email_accountActivation'] = Diffusion::sendEmail(
            $body->send_email,
            $newUser->email,
            (new EmailTemplate)->accountActivation(['code' => $code])
        );

        $path = SERVER_URL . '/accounts/activation';
        JsonResponse::created($newUser, $path, $info);
    }

    public static function update($req): void
    {
        $body = $req->body;

        if ($body) {
            if (isset($body->balance)) AdvertController::payBonus();
            else {
                Querys::table('users')->update($user = [
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

                ])->where('user_id', User::$id)
                    ->execute(function () {
                        throw new Exception('not updated user', 500);
                    });
            }
        } else throw new Exception('body empty', 400);

        JsonResponse::updated($user);
    }

    public static function destroy($req): void
    {
        if (User::$rol == ENTERPRISE) {
            Querys::table('products')->delete()
                ->where('user_id', User::$id)
                ->execute();
        }

        Querys::table('history')->delete()
            ->where('user_id', User::$id)
            ->execute();

        Querys::table('referreds')->delete()
            ->where('user_id', User::$id)
            ->execute();

        Querys::table('referreds')->delete()
            ->where('referred_id', User::$id)
            ->execute();

        Querys::table('transfers')->delete()
            ->where('user_id', User::$id)
            ->execute();

        Querys::table('accounts')->delete()
            ->where('email', User::$email)
            ->execute();

        Querys::table('users')->delete()
            ->where('user_id', User::$id)
            ->execute();

        JsonResponse::removed();
    }
}
