<?php

namespace V1\Sessions;

use Db\Querys;
use Tools\Constants;
use Tools\JsonResponse;
use Tools\Security;
use V1\Options\Time;

class Sessions extends Constants
{
    // ::::::::TEST::::::::
    public static function index()
    {
        $sessionQuery = new Querys('sessions');

        $arraySession = $sessionQuery->selectAll(self::SET_SESSIONS);
        if ($arraySession == false) throw new \Exception('not found resource', 404);

        JsonResponse::read('sessions', $arraySession);
    }

    public static function store()
    {
        $userQuery = new Querys('users');
        $sessionQuery = new Querys('sessions');

        $email = $_REQUEST['email'] ?? false;
        $username = $_REQUEST['username'] ?? false;

        if ($email) {
            $user = $userQuery->select('email', $email, 'user_id, email, password');
            if ($user == false) throw new \Exception('email not exist', 404);
        } elseif ($username) {
            $user = $userQuery->select('username', $username, 'user_id, email, password');
            if ($user == false) throw new \Exception('username not exist', 404);
        }

        $session = $sessionQuery->select('user_id', $user->user_id, 'api_token, expiration_time');
        if ($session) {
            $activeSession = self::validateExpiration($session);
            if ($activeSession) throw new \Exception('active session', 400);
        }

        self::validatePass($user);
        $token = Security::generateHash($user->email);

        $expirationTime = Time::expiration();
        $_arraySession = [
            'user_id' => $user->user_id,
            'api_token' => $token,
            'expiration_time' => $expirationTime['UTC'],
            'create_date' => Time::current('UTC')
        ];
        $sessionQuery->insert($_arraySession);

        $path = 'https://' . $_SERVER['SERVER_NAME'] . '/v1/users/' . $_GET['id'] . '/';
        $user = $userQuery->select('user_id', $user->user_id, self::SET_USERS);
        $info = [
            'expiration_time_unix' => $expirationTime['UNIX'],
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
        $newToken = Security::generateHash($user->email);

        $expirationTime = Time::expiration();
        $_arraySession = [
            'api_token' => $newToken,
            'expiration_time' => $expirationTime['UTC'],
            'update_date' => time::current('UTC')
        ];
        $sessionQuery->update('api_token', $token, $_arraySession);

        $info = [
            'expiration_time_unix' => $expirationTime['UNIX']
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

    public static function validateExpiration($session)
    {
        $sessionQuery = new Querys('sessions');

        $sessionTime = time::current('UNIX');
        $expirationTime = time::expiration('UNIX');

        if ($sessionTime >= $expirationTime) {
            $sessionQuery->delete('api_token', $session->api_token);
            return false;
        } else return true;
    }

    private static function validatePass($user)
    {
        $verify = password_verify($_REQUEST['password'], $user->password);
        if (!$verify) throw new \Exception('passsword incorrect', 401);

        $rehash = Security::rehash($user->password);
        if ($rehash == false) return;

        $userQuery = new Querys('users');
        $userQuery->update('user_id', $user->user_id, ['password' => $user->password]);
    }
}
