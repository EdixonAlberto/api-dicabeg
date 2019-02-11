 <?php

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

        $userData = Data::getDataById();

        $arrayResponse[] = [
            'Successful' => 'Verified User',
            'Response-Code' => '200',
            'User-Data' => $userData,
            'Api-Token' => $token,
            'UserData-Path' => 'https://' . $_SERVER['SERVER_NAME'] . '/v1/users/' . $_GET['id'] . '/data/'
        ];

        return $arrayResponse;
    }

    public static function verifySession()
    {
        $objSession = self::getSessionsById();
        self::verifyToken($objSession);
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
            } else {
                // TODO: Preparar respuesta mas detallada
                // $arrayFoo['Sessions'][] = [
                //     'cod error' => '401',
                //     'description' => 'Passsword inconrrect'
                // ];
                // $jsonRes = json_encode($arrayFoo);

                throw new Exception('Passsword inconrrect', 401);
            }
        } else throw new Exception("Email not exist", 400);
    }

    private static function verifyToken($objSession)
    {
        $clientToken = $_SERVER['HTTP_API_TOKEN'];
        if ($clientToken === $objSession->token) {
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
