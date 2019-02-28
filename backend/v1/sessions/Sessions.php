<?php

namespace V1\Sessions;

use Exception;
use Tools\Security;
use Tools\JsonResponse;
use V1\Options\Options;
use V1\Users\UsersQuerys;
use V1\Sessions\SessionsQuerys;

class Sessions
{
    public static function getSessionsAlls()
    {
        $sessions = SessionsQuerys::selectAlls();
        JsonResponse::read('sessions', $sessions);
    }

    public static function getSessionsById()
    {
        $session = SessionsQuerys::selectById();
        JsonResponse::read('session', $session);
    }

    public static function createSession()
    {
        $user = UsersQuerys::select('email', 'user_id, password');
        if ($user) {
            $_GET['id'] = $user->user_id;
            $session = SessionsQuerys::selectById(false);
            if (!$session) {
                self::validatePass($user->password);

                $token = Security::generateToken();
                SessionsQuerys::insert($token);

                $_SERVER['HTTP_API_TOKEN'] = $token;
                $session = SessionsQuerys::selectByToken();

                $path = 'https://' . $_SERVER['SERVER_NAME'] . '/v1/users/' . $_GET['id'] . '/';
                $expirationTime = strtotime($session->create_date . Options::expirationTime());

                $user = UsersQuerys::selectById();
                $info = [
                    'Expiration-Time' => $expirationTime,
                    'User' => $user
                ];
                JsonResponse::created('session', $session, $path, $info);

            } else throw new Exception('session exist', 404);
        } else throw new Exception('email not exist', 404);
    }

    public static function updateSession()
    {
        $token = Security::generateToken();
        SessionsQuerys::update($token);

        $_SERVER['HTTP_API_TOKEN'] = $token;
        $session = SessionsQuerys::selectByToken();

        date_default_timezone_set('America/Caracas');
        $sessionTime = date('Y-m-d H:i');

        $expirationTime = strtotime($sessionTime . Options::expirationTime());
        $info = [
            'Expiration-Time' => $expirationTime
        ];
        JsonResponse::updated('session', $session, $info);
    }

    public static function verifySession()
    {
        if (isset($_SERVER['HTTP_API_TOKEN'])) {
            $session = SessionsQuerys::selectByToken();
            if ($session) {
                $expirationTime = strtotime($session->create_date . Options::expirationTime());

                date_default_timezone_set('America/Caracas');
                $sessionTime = strtotime(date('Y-m-d H:i'));

                if ($sessionTime >= $expirationTime) {
                    SessionsQuerys::delete();
                    throw new Exception('token expired', 401);
                }
            } else throw new Exception('token incorrect', 401);
        } else throw new Exception('not found token', 404);
    }

    public static function removeSession()
    {
        SessionsQuerys::delete();
        JsonResponse::removed();
    }

    private static function validatePass($password)
    {
        $verify = password_verify($_REQUEST['password'], $password);
        if ($verify);
        else throw new Exception('passsword incorrect', 401);
    }
}
