<?php

namespace V1\Users;

use Db\Querys;
use Tools\Gui;
use Tools\Security;
use Tools\Constants;
use V1\Options\Time;
use Tools\Validations;
use Tools\JsonResponse;
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
            $user->password = '****';
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

        $email = Validations::email();
        if ($email == false) throw new \Exception('email incorrect', 400);

        $existEmail = $userQuery->select('email', $_REQUEST['email']);
        if ($existEmail) throw new \Exception('email exist', 400);

        // validacion para el codigo de registro
        $registrationCode = $_REQUEST['invite_code'] ?? null;
        if (!is_null($registrationCode)) {
            $user = $userQuery->select('invite_code', $registrationCode, 'user_id');
            if ($user == false) throw new \Exception('invite code incorrect', 400);
            $user_id = $user->user_id;
        }

        $id = Security::generateID();
        $password = Security::generateHash();
        $inviteCode = Security::generateCode();
        $username = $_REQUEST['username'] ??
            substr($email, 0, strpos($email, '@'));

        $_arrayUser = [
            'user_id' => $id,
            'player_id' => $_REQUEST['player_id'] ?? null,
            'email' => $email,
            'password' => $password,
            'username' => $username,
            'invite_code' => $inviteCode,
            'registration_code' => $registrationCode,
            'create_date' => Time::current('UTC')
        ];
        $userQuery->insert($_arrayUser);

        if (!is_null($registrationCode)) {
            $_GET['id'] = $user_id;
            $_GET['id_2'] = $id;
            $info = Referrals::store();
        } else $info = null;

        unset($_arrayUser['password']);
        $path = 'https://' . $_SERVER['SERVER_NAME'] . '/v1/sessions/';

        JsonResponse::created('user', $_arrayUser, $path, $info);
    }

    public static function update()
    {
        $userQuery = new Querys('users');

        foreach ($_REQUEST as $key => $value) {
            switch ($key) {
                case 'password':
                    $_value = Security::generateHash();
                    break;
                case 'email':
                    $_value = Validations::email();
                    break;
                case 'phone':
                    $_value = Validations::phone();
                    break;
                default:
                    $_value = $value;
                    break;
            }
            $_arrayUser[$key] = $_value;
        }
        $_arrayUser['update_date'] = Time::current('UTC');

        $userQuery->update('user_id', $_GET['id'], $_arrayUser);

        $_arrayUser['password'] = '****';
        JsonResponse::updated('user', $_arrayUser);
    }

    public static function destroy()
    {
        $referredQuery = new Querys('referrals');
        $historyQuery = new Querys('history');
        $sessionQuery = new Querys('sessions');
        $userQuery = new Querys('users');

        $user = $userQuery->select('user_id', $_GET['id'], 'user_id');
        if ($user == false) throw new \Exception('not found user', 404);

        $referredQuery->delete('referred_id', $_GET['id']);
        $historyQuery->delete('user_id', $_GET['id']);
        $sessionQuery->delete('user_id', $_GET['id']);
        $userQuery->delete('user_id', $_GET['id']);

        JsonResponse::removed();
    }
}
