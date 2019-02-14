 <?php

require_once 'SessionsQuerys.php';

class Sessions extends SessionsQuerys
{
    public static function getSessionsAlls()
    {
        $sessions = self::selectAlls();
        if ($sessions) JsonResponse::read('sessions', $sessions);
        else self::errorNotFound();
    }

    public static function getSessionsById()
    {
        $session = self::selectById();
        if ($session) JsonResponse::read('session', $session);
        else self::errorNotFound();
    }

    public static function createSession()
    {
        self::validatePass();
        $token = Security::generateToken();
        self::insert($token);

        $data = Data::getDataById();
        $path = 'https://' . $_SERVER['SERVER_NAME'] . '/v1/users/' . $_GET['id'] . '/data/';
        $info = ['Api-Token' => $token];

        JsonResponse::created('session', $data, $path, $info);
    }

    public static function verifySession()
    {
        $session = self::selectById();
        if ($session) self::verifyToken($session);
        else self::errorNotFound();
    }

    public static function removeSession()
    {
        $session = self::selectById();
        if ($session) {
            self::delete();
            JsonResponse::removed();
        } else self::errorNotFound();
    }

    private static function validatePass()
    {
        $user = AccountsQuerys::select('email', $_REQUEST['email'])[0];
        if ($user) {
            $verify = password_verify($_REQUEST['password'], $user->password);

            if ($verify) {
                $_GET['id'] = $user->user_id;
            } else throw new Exception('passsword incorrect', 401);
        } else throw new Exception('email not exist', 404);
    }

    private static function verifyToken($session)
    {
        $clientToken = $_SERVER['HTTP_API_TOKEN'];
        if ($clientToken === $session->token) {
        } else throw new Exception('token incorrect', 401);
    }

    private static function errorNotFound()
    {
        throw new Exception('not found resourse', 404);
    }
}
