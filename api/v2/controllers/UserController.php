<?php

namespace V2\Controllers;

use Exception;
use V2\Modules\Time;
use V2\Modules\Format;
use V2\Database\Querys;
use V2\Modules\Security;
use V2\Modules\Diffusion;
use V2\Libraries\SendGrid;
use V2\Modules\JsonResponse;
use V2\Modules\EmailTemplate;
use V2\Interfaces\IController;

class UserController implements IController
{
    public static function index()
    {
        $arrayUser = Querys::table('users')
            ->select(self::USERS_COLUMNS)
            ->group(GROUP_NRO)
            ->getAll(function () {
                throw new Exception('not found users', 404);
            });

        JsonResponse::read('users', $arrayUser);
    }

    public static function show()
    {
        $user = Querys::table('users')
            ->select(self::USERS_COLUMNS)
            ->where('user_id', USERS_ID)
            ->get(function () {
                throw new Exception('not found user', 404);
            });

        JsonResponse::read('user', $user);
    }

    public static function store($body)
    {
        $userQuery = Querys::table('users');

        $id = Security::generateID();
        $userQuery->insert($arrayUser = [
            'user_id' => $id,
            'player_id' => '6E37C284-27A8-47C9-8B98-2600FD937BB9',
            'email' => Format::email($body->email),
            'password' => Security::generateHash($body->password),
            'username' => substr($body->email, 0, strpos($body->email, '@')),
            'create_date' => Time::current()->utc
        ])
            ->execute();

        // ADD: Validar recurrencia de codigos,
        // asegurar unicidad con generacion recursiva del mismo
        $activationCode = Security::generateCode(6);
        Querys::table('codes')->insert([
            'user_id' => $id,
            'activation_code' => $activationCode,
            'invite_code' => Security::generateCode(8),
            'registration_code' => $body->invite_code ?? null
        ])
            ->execute();



        /*
        // validacion para el codigo de invitacion
        if (isset($body->invite_code)) {
            $user_id = $userQuery->select('user_id')
                ->where('invite_code', $body->invite_code)
                ->get(function () {
                    throw new Exception('invite code incorrect', 400);
                });
            define('REFARRALS_ID', $user_id);
        } else $body->invite_code = null;
         */




        // if (isset($body->invite_code)) { // TODO: migrar esto a la V2
        //     define('USERS_ID', $arrayUser['user_id']);
        //     $info = ReferredController::store();
        // } else $info = null;

        // Diffusion::sendNotification();



        // TODO: El idioma debe ser determinado en el
        // futuro mediante la config del usuario
        // Diffusion::sendEmail(
        //     $arrayUser['email'],
        //     EmailTemplate::accountActivation($activationCode, 'spanish')
        // );

        // $info['email'] = $emailResult;


        $path = 'https://' . $_SERVER['SERVER_NAME'] . '/v2/users/login';
        JsonResponse::created('user', $arrayUser, $path, $info = null);
    }

    public static function update($body)
    {
        Querys::table('users')->update($arrayUser = [
            'email' => Format::email($body->email),
            'password' => Security::generateHash($body->password),
            'username' => $body->username,
            'names' => $body->names,
            'lastnames' => $body->lastnames,
            'age' => $body->age,
            'avatar' => $body->avatar,
            'phone' => Format::phone($body->phone),
            'points' => $body->points,
            'money' => $body->money,
            'update_date' => Time::current()->utc
        ])->where('user_id', USERS_ID)->execute();

        unset($arrayUser['password']);

        JsonResponse::updated('user', $arrayUser);
    }

    public static function destroy()
    {
        Querys::table('codes')->delete('codes')
            ->where('user_id', USERS_ID)
            ->execute();

        // Querys::table('referrals')->delete('referrals')
        //     ->where('referred_id', USERS_ID)
        //     ->execute();

        // Querys::table('history')->delete('history')
        //     ->where('user_id', USERS_ID)
        //     ->execute();

        Querys::table('users')->delete('users')
            ->where('user_id', USERS_ID)
            ->execute();

        JsonResponse::removed();
    }
}
