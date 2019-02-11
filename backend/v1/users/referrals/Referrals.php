<?php

require_once 'ReferralsQuerys.php';

class Referrals extends ReferralsQuerys
{
    public static function getReferralsAlls()
    {
        $query = self::selectAlls();
        $arrayResponse = GeneralMethods::processJson($query);

        return $arrayResponse;
    }

    public static function getReferralsById()
    {
        $existReferred = self::checkoutReferral($arrayReferrals, $index);

        if ($existReferred) {
            $arrayResponse[] = $arrayReferrals[$index];
        } else throw new Exception('Referred does not exist', 400);

        return $arrayResponse;
    }

    public static function insertReferrals()
    {
        $existReferred = self::checkoutReferral($arrayReferrals);
        if (!$existReferred) {
            $arrayReferrals[] = self::referredDataGenerator();
            $jsonReferrals = json_encode($arrayReferrals);

            $result = self::update($jsonReferrals);
            self::interpretResult($result);

            $arrayResponse[] = ['Successful' => 'Created referred'];

        } else throw new Exception('Referred exist', 400);

        return $arrayResponse;
    }

    public static function deleteReferrals()
    {
        $existReferred = self::checkoutReferral($arrayReferrals, $index);

        if ($existReferred) {
            unset($arrayReferrals[$index]); // Se elimina el objeto en la posicion 'index' del array
            $jsonReferrals = json_encode($arrayReferrals);

            $result = self::update($jsonReferrals);
            self::interpretResult($result);

            $arrayResponse[] = ['Successful' => 'Deleted referred'];
        } else throw new Exception('Referred does not exist', 400);

        return $arrayResponse;
    }

    private static function checkoutReferral(&$arrayReferrals, &$index = null)
    {
        $arrayReferrals = self::getReferralsAlls();

        if ($arrayReferrals) {
            for ($i = 0; $i < count($arrayReferrals); $i++) {
                $objReferred = $arrayReferrals[$i];

                if ($_GET['id_2'] == $objReferred->user_id) {
                    $index = $i;
                    return true;
                }
            }
        } else return false;
    }

    private static function referredDataGenerator()
    {
        $id = $_GET['id'];
        $_GET['id'] = $_GET['id_2'];

        $query = DataQuerys::selectById("user_id, username, image, phone");
        $objData = GeneralMethods::processById($query);

        if ($objData) {
            $objData->create_date = date("d-m-Y");
        } else throw new Exception('Referred does not exist', 400);

        $_GET['id'] = $id;
        return $objData;
    }

    private static function interpretResult($result)
    {
        $error = $result->errorInfo();
        $errorExist = !is_null($error[1]);
        if ($errorExist) {
            throw new Exception($error[2], 400);
        }
    }
}
