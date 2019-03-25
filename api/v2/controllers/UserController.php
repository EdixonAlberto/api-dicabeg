<?php

namespace V2\Controllers;

use Exception;
use V2\Modules\Time;
use V2\Database\Querys;
use V2\Modules\Security;
use V2\Modules\Validate;
use V2\Modules\JsonResponse;
use V2\Interfaces\SetInterface;
use V2\Interfaces\ControllerInterface;

class UserController implements ControllerInterface, SetInterface
{
    public static function index()
    {
        $arrayUser = Querys::table('users')
            ->select(self::SET_USERS)
            ->getAll();

        if ($arrayUser == false) throw new Exception('not found users', 404);
        foreach ($arrayUser as $user) {
            $user->password = 'Estas loco!! si piensas que te voy a dar mi clave';
            $_arrayUser[] = $user;
        }
        JsonResponse::read('users', $_arrayUser);
    }

    public static function show()
    {
        $userQuery = new Querys('users');

        $user = $userQuery
            ->select(self::SET_USERS)
            ->where('user_id', ID1)
            ->get();

        if ($user == false) throw new Exception('not found user', 404);
        JsonResponse::read('user', $user);
    }

    public static function store()
    {
        $userQuery = new Querys('users');

        $email = Validations::email();
        if ($email == false) throw new Exception('email incorrect', 400);

        $existEmail = $userQuery
            ->select('email')
            ->where('email', $email)
            ->get();
        if ($existEmail) throw new Exception('email exist', 400);

        // validacion para el codigo de registro
        $registrationCode = $_REQUEST['invite_code'] ?? null;
        if (!is_null($registrationCode)) {
            $user = $userQuery
                ->select('user_id')
                ->where('invite_code', $registrationCode)
                ->get();
            if ($user == false) throw new Exception('invite code incorrect', 400);
            $user_id = $user->user_id;
        }

        $id = Security::generateID();
        $password = Security::generateHash();
        $inviteCode = Security::generateCode();
        $username = substr($email, 0, strpos($email, '@'));

        $userQuery->insert([
            'user_id' => $id,
            'email' => $email,
            'password' => $password,
            'invite_code' => $inviteCode,
            'registration_code' => $registrationCode,
            'username' => $username,
            'create_date' => Time::current('UTC')
        ]);

        if (!is_null($registrationCode)) {
            $_GET['id'] = $user_id;
            $_GET['id_2'] = $id;
            $info = ReferredController::store();
        } else $info = null;

        unset($_arrayUser['password']);
        $path = 'https://' . $_SERVER['SERVER_NAME'] . '/v2/sessions/';

        JsonResponse::created('user', $_arrayUser, $path, $info);
    }

    public static function update()
    {
        $userQuery = new Querys('users');

        $user = (array)$userQuery
            ->select(self::SET_USERS . ', password')
            ->where('user_id', $_GET['id'])
            ->get();

        // se descartan los codigos para referido
        unset($user['invite_code'], $user['registration_code']);
        foreach ($user as $_key => $_value) {
            $_keyFound = false;
            foreach ($_REQUEST as $key => $value) {
                if ($_key == $key) {
                    $_arrayUser[$_key] = ($key == 'password') ?
                        Security::generateHash() : ($key == 'email') ?
                        Validations::email() : ($key == 'phone') ?
                        Validations::phone() : $value;
                    $_keyFound = true;
                }
            }
            if (!$_keyFound and $_key != 'user_id') {
                $_arrayUser[$_key] = $_value;
            }
        }
        $_arrayUser['update_date'] = Time::current('UTC');

        $userQuery->update('user_id', $_GET['id'], $_arrayUser);

        unset($_arrayUser['password']);
        JsonResponse::updated('user', $_arrayUser);
    }

    public static function destroy()
    {
        $user = Querys::table('users')
            ->select('user_id')
            ->where('user_id', $_GET['id'])
            ->get();
        if ($user == false) throw new Exception('not found user', 404);

        Querys::delete('sessions')->where('user_id', $_GET['id'])->execute();
        Querys::delete('referrals')->where('referred_id', $_GET['id'])->execute();
        Querys::delete('users')->where('user_id', $_GET['id'])->execute();

        JsonResponse::removed();
    }
}
