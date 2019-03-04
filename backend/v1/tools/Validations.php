<?php

namespace Tools;

use Exception;

//2639569E-78C0-4A5D-83EC-20A7CD535210
//71508f6f-b61d-a303-22d3-49ecf251f618




class Validations
{
    public static function gui()
    {
        // No se valida si los parametros en GET estan seteados, porque apache asegura esto con las redirecciones
        // foreach ($_GET as $gui) {

        $array = array();
        if (preg_match('/^[A-Z0-9\-]{0,38}$/', '2639569E-78C0-4A5D-83EC-20A7CD535210', $array)) {
            $resp = 1;
        } else {
            $resp = 0;
        }
        var_dump($resp, $array);
        die;

            // if ($id === 'alls');
            // elseif (strlen($id) == 36) {
            //     continue; //TODO: Validar formato de gui, ej: 2639569E-78C0-4A5D-83EC-20A7CD535210
            // } else throw new Exception("id incorrect", 400);
        // }
    }

    public static function token()
    {
        $token = $_SERVER['HTTP_API_TOKEN'] ?? false;
        if ($token == false) throw new Exception('not found token', 404);
        return $token;
    }

    public static function parameters($origin)
    {
        return;

        // TODO: Falta hacer toda la verificacion de parametros segun el vervo http usado
        switch ($origin) {
            case 'USERS':
                if ($_REQUEST) {
                    self::parametersUsers();
                } else throw new Exception('No parameter found');
                break;

            case 'DATA':
                if ($_REQUEST) {
                    self::parametersData();
                } else throw new Exception('No parameter found');
                break;
            case 'REFERRALS':

                break;
        }
    }
    /*
    private function parametersUsers()
    {
        if (isset($_REQUEST['email'])) {
            $email = $_REQUEST['email'];
            if (!empty($email)) {
            } else throw new Exception('Parameter: email is empty');
        } else throw new Exception("Parameter: email not found");
        if (isset($_REQUEST['password'])) {
            $pass = $_REQUEST['password'];
            if (!empty($pass)) {
            } else throw new Exception('Parameter: password is empty');
        } else throw new Exception("Parameter: password not found");
    }

    private function parametersData()
    {
        $arraySet = [
            'username',
            'names',
            'lastname',
            'age',
            'image',
            'phone',
            'points',
            'movile_data',
        ];
        foreach ($arraySet as $defaultKey) {
            $keyFound = false;
            foreach ($_REQUEST as $entryKey => $value) {
                if ($defaultKey === $entryKey) {
                    if (empty($value)) {
                        $_REQUEST[] = null;
                    } else {
                        valueValidate($entryKey);

                    }
                    $keyFound = true;
                    break;
                }
            }
            if (!$keyFound) {
                $arrayValuesValidated[] = null;
            }
        }

        function updateData(&$response)
        {
            parse_str(file_get_contents('php://input'), $_PATCH);
            foreach ($_PATCH as $value) {
            }
            $id = $_PATCH['id'];
            $names = $_PATCH['names'];
            // $lastnames = $_PATCH['lastnames'];
            // $age = $_PATCH['age'];
            // $image = $_PATCH['image'];
            // $phone = $_PATCH['phone'];
            // $points = $_PATCH['points'];
            // $referrals = $_PATCH['referrals'];

            $query = new querysData();
            $query->update($id, $names, $lastnames, $age, $image, $phone, $points, $referrals);

            if (is_null($query->errorInfo()[1])) {
                $response = true;
            } else {
                echo ($query->errorInfo());
            }
        }
    }
     */
}
