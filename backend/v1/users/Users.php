<?php

namespace V1\Users;

use Db\Querys;
use Tools\Constants;
use Tools\Gui;
use Tools\JsonResponse;
use Tools\Security;
use V1\Users\Referrals\Referrals;

class Users extends Constants
{
    // ::::::::TEST::::::::
    public static function index()
    {
        $userQuery = new Querys('users');

        $arrayUser = $userQuery->selectAll('password, ' . self::SET_USERS);
        if ($arrayUser == false) throw new \Exception('not found users', 404);

        foreach ($arrayUser as $user) {
            $user->password = 'Estas loco!! si piensas que te voy a dar mi clave';
            $_arrayUser[] = $user;
        }
        JsonResponse::read('users', $_arrayUser);
    }

    public static function show()
    {
        $userQuery = new Querys('users');

        $user = $userQuery->select('user_id', $_GET['id'], self::SET_USERS);
        if ($user == false) throw new \Exception('not found user', 404);

        JsonResponse::read('user', $user);
    }

    public static function store()
    {
        $userQuery = new Querys('users');
        $referredQuery = new Querys('referrals');

        $user = $userQuery->select('email', $_REQUEST['email']);
        if ($user) throw new \Exception('email exist', 400);

        // validacion para el codigo de registro
        $registrationCode = $_REQUEST['invite_code'] ?? null;
        if (!is_null($registrationCode)) {
            $user = $userQuery->select('invite_code', $registrationCode, 'user_id');
            if ($user == false) throw new \Exception('invite code incorrect', 400);
            $user_id = $user->user_id;
        }

        $id = Gui::generate();
        $email = $_REQUEST['email'];
        $password = Security::encryptPassword($_REQUEST['password']);
        $inviteCode = Gui::generate();
        $username = substr($email, 0, strpos($email, '@'));

        $_arrayUser = [
            'user_id' => $id,
            'email' => $email,
            'password' => $password,
            'invite_code' => $inviteCode,
            'registration_code' => $registrationCode,
            'username' => $username
        ];

        date_default_timezone_set('America/Caracas');
        $_arrayUser['create_date'] = date(self::TIME_FORMAT);
        $userQuery->insert($_arrayUser);

        if (!is_null($registrationCode)) {
            $_GET['id'] = $user_id;
            $_GET['id_2'] = $id;
            $info = Referrals::store();
        } else $info = null;

        // TODO: enviar correo con token para _authentication_

        unset($_arrayUser['password']);
        $path = 'https://' . $_SERVER['SERVER_NAME'] . '/v1/sessions/';

        JsonResponse::created('user', $_arrayUser, $path, $info);
    }

    public static function update()
    {
        $userQuery = new Querys('users');

        $user = (array)$userQuery->select('user_id', $_GET['id'], self::SET_USERS . ', password');

        // se descartan los codigos para referido
        unset($user['invite_code'], $user['registration_code']);

        foreach ($user as $_key => $_value) {
            $_keyFound = false;
            foreach ($_REQUEST as $key => $value) {
                if ($_key == $key) {
                    $_arrayUser[$_key] = ($key == 'password') ?
                        Security::encryptPassword($_REQUEST['password']) :
                        $value;
                    $_keyFound = true;
                }
            }
            if (!$_keyFound and $_key != 'user_id') {
                $_arrayUser[$_key] = $_value;
            }
        }
        date_default_timezone_set('America/Caracas');
        $_arrayUser['update_date'] = date(self::TIME_FORMAT);

        $userQuery->update('user_id', $_GET['id'], $_arrayUser);
        unset($_arrayUser['password']);
        JsonResponse::updated('user', $_arrayUser);
    }

    public static function destroy()
    {
        $sessionQuery = new Querys('sessions');
        $referredQuery = new Querys('referrals');
        $userQuery = new Querys('users');

        $user = $userQuery->select('user_id', $_GET['id'], 'user_id');
        if ($user == false) throw new \Exception('not found user', 404);

        $sessionQuery->delete('user_id', $_GET['id']);
        $referredQuery->delete('referred_id', $_GET['id']);
        $userQuery->delete('user_id', $_GET['id']);

        JsonResponse::removed();
    }
}
