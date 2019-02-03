 <?php

class Sessions
{
 // Iniciar Sesion
    public static function signIn($POST)
    {
        $existUser = self::checkout($POST);

        if ($existUser) {
            foreach ($POST as $key => $value) {
                if ($key = 'password') {
                    $_key = $key;
                    $_value = $value;
                }
            }

            $result = self::getById($_value, $_key);
            $rows = $result->rowCount();

            if ($rows) {
                http_response_code(200);
                $arrayResponse[] = ['Successful' => 'Verified User'];
            } else {
                http_response_code(400);
                $arrayResponse[] = ['Error' => 'Password Incorrect'];
            }
        } else {
            http_response_code(400);
            $arrayResponse[] = ['Warning' => 'User Not Exist'];
        }

        return self::response($arrayResponse);
    }
}