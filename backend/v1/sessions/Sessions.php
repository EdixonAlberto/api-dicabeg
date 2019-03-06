<?php

namespace V1\Sessions;

use Db\Querys;
use Tools\Constants;
use Tools\JsonResponse;
use Tools\Security;
use Tools\Validations;
use V1\Options\Options;

class Sessions extends Constants
{
    // ::::::::TEST::::::::
    public static function index()
    {
        $sessionQuery = new Querys('sessions');

        $arraySession = $sessionQuery->selectAll(self::SET_SESSION);
        if ($arraySession == false) throw new \Exception('not found resource', 404);

        JsonResponse::read('sessions', $arraySession);
    }

    public static function store()
    {
        $userQuery = new Querys('users');
        $sessionQuery = new Querys('sessions');

        $user = $userQuery->select('email', $_REQUEST['email'], 'user_id, email, password');
        if ($user == false) throw new \Exception('email not exist', 404);

        $session = $sessionQuery->select('user_id', $user->user_id, 'api_token, expiration_time');
        if ($session) {
            $activeSession = self::validateExpiration($session);
            if ($activeSession) throw new \Exception('active session', 400);
        }

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

        $token = $_SERVER['HTTP_API_TOKEN'];
        $session = $sessionQuery->select('api_token', $token, 'user_id, api_token, expiration_time');
        if ($session == false) throw new \Exception('token incorrect', 401);

        $activeSession = self::validateExpiration($session);
        if ($activeSession == false) throw new \Exception('token expired', 401);

        $user = $userQuery->select('user_id', $session->user_id, 'email');
        $newToken = Security::generateToken($user->email);

        date_default_timezone_set('America/Caracas');
        $updateDate = date(self::TIME_FORMAT);
        $expirationTime = strtotime($updateDate . Options::expirationTime());

        $_arraySession = [
            'api_token' => $newToken,
            'expiration_time' => date(self::TIME_FORMAT, $expirationTime),
            'update_date' => $updateDate
        ];
        $sessionQuery->update('api_token', $token, $_arraySession);

        $info = [
            'expiration_time_unix' => $expirationTime
        ];
        JsonResponse::updated('session', $_arraySession, $info);
    }

    public static function destroy()
    {
        $sessionQuery = new Querys('sessions');

        $token = $_SERVER['HTTP_API_TOKEN'];
        $sessionQuery->delete('api_token', $token);
        JsonResponse::removed();
    }

    public static function verifySession()
    {
        $sessionQuery = new Querys('sessions');

        $token = Validations::token();
        $session = $sessionQuery->select('api_token', $token, 'api_token, expiration_time');
        if ($session == false) throw new \Exception('token incorrect', 401);

        $activeSession = self::validateExpiration($session);
        if ($activeSession == false) throw new \Exception('token expired', 401);
    }

    private static function validateExpiration($session)
    {
        $sessionQuery = new Querys('sessions');

        date_default_timezone_set('America/Caracas');
        $expirationTime = $session->expiration_time;
        $sessionTime = date(self::TIME_FORMAT);

        if ($sessionTime >= $expirationTime) {
            $sessionQuery->delete('api_token', $session->api_token);
            return false;
        } else return true;
    }

    private static function validatePass($password)
    {
        $verify = password_verify($_REQUEST['password'], $password);
        if (!$verify) throw new \Exception('passsword incorrect', 401);
    }
}
