<?php

require_once './UsersQuerys.php';

class Users
{
    public static function getUsersAlls()
    {
        $users = UsersQuerys::selectAlls();
        if ($users) JsonResponse::read('users', $users);
        else self::errorNotFound();
    }

    public static function getUserById()
    {
        $user = UsersQuerys::selectById();
        if ($user) JsonResponse::read('user', $user);
        else self::errorNotFound();
    }

    public static function createUser()
    {
        $user = UsersQuerys::select('email', $_REQUEST['email']);
        if (!$user) {
            $_GET['id'] = Gui::generate();

            $asReferred = isset($_REQUEST['invite-code']);
            if ($asReferred) {
                Referrals::createReferrals();
                $info = 'referred added';
            } else $info = null;

            $email = $_REQUEST['email'];
            $password = Security::encryptPassword($_REQUEST['password']);
            $inviteCode = Gui::generate();
            $username = substr($email, 0, strpos($email, '@'));

            $arrayUser[] = $email;
            $arrayUser[] = $password;
            $arrayUser[] = $inviteCode;
            $arrayUser[] = $username;

            UsersQuerys::insert($arrayUser);
            ReferralsQuerys::insert();

            $user = UsersQuerys::selectById('user_id, email, invite_code, username');
            $path = 'https://' . $_SERVER['SERVER_NAME'] . '/v1/sessions/';

            JsonResponse::created('user', $user, $path, $info);

        } else throw new Exception('email exist', 400);
    }

    public static function updateUser()
    {
        $user = (array)UsersQuerys::selectById('*');
        if ($user) {
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

        } else self::errorNotFound();
    }

    public static function deleteUser()
    {
        $user = UsersQuerys::selectById();
        if ($user) {
            SessionsQuerys::delete();
            ReferralsQuerys::delete();
            UsersQuerys::delete();

            JsonResponse::removed('user');

        } else self::errorNotFound();
    }

    private static function errorNotFound()
    {
        throw new Exception('not found resourse', 404);
    }
}
