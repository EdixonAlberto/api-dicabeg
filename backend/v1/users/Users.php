<?php

require_once './UsersQuerys.php';

class Users
{
    public static function getUsersAlls()
    {
        $users = UsersQuerys::selectAlls();
        JsonResponse::read('users', $users);
    }

    public static function getUserById()
    {
        $user = UsersQuerys::selectById();
        JsonResponse::read('user', $user);
    }

    public static function createUser()
    {
        $existUser = UsersQuerys::search('email');
        if (!$existUser) {

            $id = Gui::generate();
            // FIXME: El referido se agrega aunque el usuario no se agregue
            $info = Referrals::createReferred($id);
            $email = $_REQUEST['email'];
            $password = Security::encryptPassword($_REQUEST['password']);
            $inviteCode = Gui::generate();
            $username = substr($email, 0, strpos($email, '@'));

            $arrayUser[] = $email;
            $arrayUser[] = $password;
            $arrayUser[] = $inviteCode;
            $arrayUser[] = $_REQUEST['invite_code'];
            $arrayUser[] = $username;

            $_GET['id'] = $id;
            UsersQuerys::insert($arrayUser);
            ReferralsQuerys::insert($inviteCode);

            $user = UsersQuerys::selectById('user_id, email, invite_code, username');
            $path = 'https://' . $_SERVER['SERVER_NAME'] . '/v1/sessions/';
            JsonResponse::created('user', $user, $path, $info);

        } else throw new Exception('email exist', 400);
    }

    public static function updateUser()
    {
        $user = (array)UsersQuerys::selectById('*');
        // se descartan los codigos para referido
        unset($user['invite_code'], $user['registration_code']);
        $newUser = $_REQUEST;

        foreach ($user as $_key => $_value) {
            $_keyFound = false;
            foreach ($newUser as $key => $value) {
                if ($_key == $key) {
                    $arrayUser[] = ($key == 'password') ?
                        Security::encryptPassword($_REQUEST['password']) :
                        $value;
                    $_keyFound = true;
                }
            }
            if (!$_keyFound and $_key != 'user_id') {
                $arrayUser[] = $_value;
            }
        }
        UsersQuerys::update($arrayUser);

        $user = UsersQuerys::selectById();
        JsonResponse::updated('user', $user);
    }

    public static function removeUser()
    {
        ReferralsQuerys::delete();
        SessionsQuerys::delete();
        $user = UsersQuerys::selectById('registration_code');
        UsersQuerys::delete();

        if (is_null($user)) {
            $_REQUEST['invite_code'] = $user->registration_code;
            $user = UsersQuerys::select('invite_code', 'user_id');
            $_GET['id_2'] = $_GET['id'];
            $_GET['id'] = $user->user_id;
            Referrals::removeReferred(false);
        }

        JsonResponse::removed();
    }
}
