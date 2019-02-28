<?php

namespace V1\Sessions;

use Db\Querys;
use Exception;
use Tools\JsonResponse;
use Tools\Security;
use V1\Options\Options;
use V1\Sessions\SessionsQuerys;

class Sessions
{
    protected const SET_SESSION = 'user_id, api_token, create_token';
    protected const SET = 'user_id, email, invite_code, registration_code, username, names, lastnames, age, avatar, phone, points, movile_data, create_date, update_date';
    protected const TIME = 'Y-m-d H:i:00';

    public static function index()
    {
        $sessionQuery = new Querys('sessions');

        $arraySession = $sessionQuery->select('user_id', $_GET['id'], self::SET_SESSION);
        if ($arraySession == false) throw new Exception('not found resource', 404);

        JsonResponse::read('sessions', $arraySession);
    }

    // public static function getSessionsById()
    // {
    //     $session = SessionsQuerys::selectById();
    //     JsonResponse::read('session', $session);
    // }

    public static function store()
    {
        $userQuery = new Querys('users');
        $sessionQuery = new Querys('sessions');

        $arrayUser = $userQuery->select('email', $_REQUEST['email'], 'user_id, password');
        if ($arrayUser == false) throw new Exception('email not exist', 404);

        $user = $arrayUser[0];
        $user_id = $user->user_id;

        $arraySession = $sessionQuery->select('user_id', $user_id);
        if ($arraySession !== false) throw new Exception('session exist', 404);

        self::validatePass($user->password);
        $token = Security::generateToken();

        date_default_timezone_set('America/Caracas');
        $_arraySession = [
            'user_id' => $user_id,
            'api_token' => $token,
            'create_date' => date(self::TIME)
        ];
        $sessionQuery->insert($_arraySession);

        $path = 'https://' . $_SERVER['SERVER_NAME'] . '/v1/users/' . $_GET['id'] . '/';
        $expirationTime = strtotime($_arraySession['create_date'] . Options::expirationTime());
        $arrayUser = $userQuery->select('user_id', $user_id, self::SET);
        $info = [
            'expiration-time' => $expirationTime,
            'user' => $arrayUser[0]
        ];
        JsonResponse::created('session', $_arraySession, $path, $info);
    }

    public static function update()
    {
        $sessionQuery = new Querys('sessions');

        $token = Security::generateToken();
        date_default_timezone_set('America/Caracas');
        $_arraySession = [
            'api_token' => $token,
            'update_date' => date(self::TIME)
        ];

        $oldToken = $_SERVER['HTTP_API_TOKEN'];
        $sessionQuery->update('api_token', $oldToken, $_arraySession);

        $expirationTime = strtotime($_arraySession['update_date'] . Options::expirationTime());
        $info = [
            'expiration-time' => $expirationTime
        ];
        JsonResponse::updated('session', $_arraySession, $info);
    }

    public static function verifySession()
    {
        $sessionQuery = new Querys('sessions');

        $token = $_SERVER['HTTP_API_TOKEN'] ?? false;
        if ($token == false) throw new Exception('not found token', 404);

        $arraySession = $sessionQuery->select('api_token', $token, 'create_date');
        if ($arraySession == false) throw new Exception('token incorrect', 401);

        $expirationTime = strtotime($arraySession[0]->create_date . Options::expirationTime());

        date_default_timezone_set('America/Caracas');
        $sessionTime = strtotime(date('Y-m-d H:i'));

        if ($sessionTime >= $expirationTime) {
            SessionsQuerys::delete();
            throw new Exception('token expired', 401);
        }
    }

    public static function destroy()
    {
        $sessionQuery = new Querys('sessions');

        $token = $_SERVER['HTTP_API_TOKEN'];
        $sessionQuery->delete('api_token', $token);
        JsonResponse::removed();
    }

    protected static function validatePass($password)
    {
        $verify = password_verify($_REQUEST['password'], $password);
        if (!$verify) throw new Exception('passsword incorrect', 401);
    }
}
