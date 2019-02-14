 <?php

require_once 'SessionsQuerys.php';

class Sessions extends SessionsQuerys
{
    public static function getSessionsAlls()
    {
        $sessions = self::selectAlls();
        if ($sessions) return $sessions;
    }

    public static function getSessionsById()
    {
        $session = self::selectById()[0];
        if ($session) return $session;
        else self::errorNotFound();
    }

    public static function createSession()
    {
        self::validatePass();
        $token = Security::generateToken();

        self::insert($token);
        $data = Data::getDataById()[0];
        $path = '/v1/users/' . $_GET['id'] . '/data/';

        $response = [
            'session' => [
                'Status' => '200',
                'Response' => 'Successful',
                'Description' => 'created session',
                'Data' => $data,
                'Authorization' => 'Bearer ' . $token,
                'Path' => 'https://' . $_SERVER['SERVER_NAME'] . $path
            ]
        ];
        JsonResponse::send($response);
    }

    public static function verifySession()
    {
        $session = self::getSessionsById();
        self::verifyToken($session);
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
