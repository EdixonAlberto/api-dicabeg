 <?php

require_once '../tools/Security.php';
require_once '../tools/GeneralMethods.php';
require_once '../users/accounts/AccountsQuerys.php';
require_once 'SessionsQuerys.php';

class Sessions extends SessionsQuerys
{
    public static function getSessionsAlls()
    {
        $query = self::selectAlls();
        return GeneralMethods::processAlls($query);
    }

    public static function getSessionsById()
    {
        $query = self::selectById();
        $result = GeneralMethods::processById($query);
        if ($result) {
            return $result;
        } else throw new Exception('Session don no exist', 400);
    }

    public static function createSession()
    {
        self::validatePass();
        $token = Security::generateToken();

        $result = self::insert($token);
        self::interpretResult($result);

        $arrayResponse[] = [
            'Successful' => 'Verified User',
            'id' => $_GET['id'],
            'API-Token' => $token,
            'UserData-Path' => 'https://' . $_SERVER['SERVER_NAME'] . '/v1/users/' . $_GET['id'] . '/data/'
        ];

        return $arrayResponse;
    }

    public static function verifySession()
    {
        $session = self::getSessionsById()[0];
        self::verifyToken($session);
    }

    public static function removeSession()
    {
        $existingSession = self::checkout('id');
        if ($existingSession) {
            $result = self::delete();
            self::interpretResult($result);

            $arrayResponse[] = ['Successful' => 'Deleted session'];
        } else throw new Exception('Session does not exist', 400);

        return $arrayResponse;
    }

    private static function validatePass()
    {
        $email = $_REQUEST['email'];
        // TODO: Revisar si se puede consultar por id, para usar get, en ves de select
        $query = AccountsQuerys::selectById($email, 'email');
        $row = $query->rowCount();
        if ($row) {
            $user = $query->fetch(PDO::FETCH_ASSOC);

            $passwordEncripted = $user['password'];
            $password = $_REQUEST['password'];
            $verify = password_verify($password, $passwordEncripted);

            if ($verify) {
                $_GET['id'] = $user['user_id'];
            } else throw new Exception("Passsword inconrrect", 400);
        } else throw new Exception("Email not exist", 400);
    }

    private static function verifyToken($session)
    {
        $clientToken = $_SERVER['HTTP_API_TOKEN'];
        if ($clientToken === $session['token']) {
        } else throw new Exception('Token incorrect', 400);
    }

    private static function interpretResult($result)
    {
        $error = $result->errorInfo();
        $errorExist = !is_null($error[1]);
        if ($errorExist) {
            throw new Exception($error[2], 400);
        }
    }

    private static function checkout($field)
    {
        if ($field == 'id') {
            $result = self::selectById($_GET['id']);
        } else if ($field == 'email') {
            $result = AccountsQuerys::selectById($_REQUEST['email'], 'email');
        }
        $rows = $result->rowCount();
        return $rows ? true : false;
    }
}
