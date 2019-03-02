<?php

namespace V1\Sessions;

use Db\Querys;
use Exception;
use Tools\JsonResponse;
use Tools\Security;
use V1\Options\Options;

class Sessions
{
    protected const SET_SESSION = 'user_id, api_token, expiration_time, create_token';
    protected const SET_USER = 'user_id, email, invite_code, registration_code, username, names, lastnames, age, avatar, phone, points, movile_data, create_date, update_date';
    protected const TIME_FORMAT = 'Y-m-d H:i:s';

    // ::::::::TEST::::::::
    public static function index()
    {
        $sessionQuery = new Querys('sessions');

        $arraySession = $sessionQuery->select('user_id', $_GET['id'], self::SET_SESSION);
        if ($arraySession == false) throw new Exception('not found resource', 404);

        JsonResponse::read('sessions', $arraySession);
    }

    public static function store()
    {
        $userQuery = new Querys('users');
        $sessionQuery = new Querys('sessions');

        $user = $userQuery->select('email', $_REQUEST['email'], 'user_id, password, email');
        if ($user == false) throw new Exception('email not exist', 404);

        $session = $sessionQuery->select('user_id', $user->user_id);
        if ($session != false) throw new Exception('session exist', 404);

        self::validatePass($user->password);
        $token = Security::generateToken($user->email);

        date_default_timezone_set('America/Caracas');
        $sessionTime = date(self::TIME_FORMAT);
        $expirationTime = strtotime($sessionTime . Options::expirationTime());

        $_arraySession = [
            'user_id' => $user->user_id,
            'api_token' => $token,
            'expiration_time' => date(self::TIME_FORMAT, $expirationTime),
            'create_date' => $sessionTime
        ];
        $sessionQuery->insert($_arraySession);

        $path = 'https://' . $_SERVER['SERVER_NAME'] . '/v1/users/' . $_GET['id'] . '/';
        $user = $userQuery->select('user_id', $user->user_id, self::SET_USER);
        $info = [
            'expiration_time_unix' => $expirationTime,
            'user' => $user
        ];
        JsonResponse::created('session', $_arraySession, $path, $info);
    }

    public static function update()
    {
        $sessionQuery = new Querys('sessions');
        $userQuery = new Querys('users');

        $oldToken = $_SERVER['HTTP_API_TOKEN'];
        $session = $sessionQuery->select('api_token', $oldToken, 'user_id');
        if ($session == false) throw new Exception('token incorrect', 401);

        $user = $userQuery->select('user_id', $session->user_id, 'email');

        $token = Security::generateToken($user->email);
        date_default_timezone_set('America/Caracas');
        $updateDate = date(self::TIME_FORMAT);
        $expirationTime = strtotime($updateDate . Options::expirationTime());

        $_arraySession = [
            'api_token' => $token,
            'expiration_time' => date(self::TIME_FORMAT, $expirationTime),
            'update_date' => $updateDate
        ];

        $sessionQuery->update('api_token', $oldToken, $_arraySession);

        $info = [
            'expiration_time_unix' => $expirationTime
        ];
        JsonResponse::updated('session', $_arraySession, $info);
    }

    public static function verifySession()
    {
        $sessionQuery = new Querys('sessions');

        $token = $_SERVER['HTTP_API_TOKEN'] ?? false;
        if ($token == false) throw new Exception('not found token', 404);

        $session = $sessionQuery->select('api_token', $token, 'expiration_time');
        if ($session == false) throw new Exception('not found session', 404);

        date_default_timezone_set('America/Caracas');
        $expirationTime = strtotime($session->expiration_time);
        $sessionTime = strtotime(date('Y-m-d H:i'));

        if ($sessionTime >= $expirationTime) {
            $sessionQuery->delete('api_token', $token);
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
