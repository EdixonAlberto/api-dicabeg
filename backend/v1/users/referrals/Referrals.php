<?php

require_once 'ReferralsQuerys.php';

class Referrals
{
    public static function getReferralsAlls()
    {
        $referrals = ReferralsQuerys::selectAlls();
        $jsonReferrals = $referrals->referrals_data;
        if ($jsonReferrals) {
            $arrayReferrals = json_decode($jsonReferrals);

            for ($i = 0; $i < count($arrayReferrals); $i++) {
                $referred = $arrayReferrals[$i];
                $_GET['id'] = $referred->referred_id;

                $user = AccountsQuerys::select('user_id', $_GET['id'], 'email');
                $referred_data = DataQuerys::select('user_id', $_GET['id'], 'username, image, phone');
                $referred_data->email = $user->email;
                $referred_data->create_date = $referred->create_date;
                $arrayReferrals_data[] = $referred_data;
            }
            JsonResponse::read('referrals', $arrayReferrals_data);

        } else self::errorNotFound();
    }

    public static function getReferralsById()
    {
        // TODO: redefinir los querys para hacer consultas espedificas en el json
        $referrals = ReferralsQuerys::selectAlls();
        $jsonReferrals = $referrals->referrals_data;
        if ($jsonReferrals) {
            $arrayReferrals = json_decode($jsonReferrals);

            // TODO: crear una funcion para automatizar esta parte
            for ($i = 0; $i < count($arrayReferrals); $i++) {
                $referred = $arrayReferrals[$i];
                $_GET['id'] = $referred->referred_id;

                if ($_GET['id'] == $_GET['id_2']) {
                    $user = AccountsQuerys::selectById('email');
                    $referred_data = DataQuerys::selectById('username, image, phone');
                    $referred_data->email = $user->email;
                    $referred_data->create_date = $referred->create_date;
                }
            }
            if (isset($referred_data)) {
                JsonResponse::read('referred', $referred_data);
            } else throw new Exception('referred not exist', 404);

        } else self::errorNotFound();
    }

    public static function createReferrals()
    {
        $referrals = ReferralsQuerys::selectByCode();
        if ($referrals) {
            $arrayReferrals = json_decode($referrals->referrals_data);

            $arrayReferrals[] = (object)[
                'referred_id' => $_GET['id'],
                'create_date' => date('Y-m-d h:i:s')
            ];

            $jsonReferrals = json_encode($arrayReferrals);
            ReferralsQuerys::update('invite_code', $_REQUEST['invite-code'], $jsonReferrals);

        } else throw new Exception('Incorrect invite code', 400);
    }

    public static function createCode()
    {
        $code = Gui::generate();
        ReferralsQuerys::updateCode($code);

        JsonResponse::created('inviteCode', $code);
    }

    public static function deleteReferrals()
    {
        $referrals = ReferralsQuerys::selectAlls();
        $jsonReferrals = $referrals->referrals_data;
        $arrayReferrals = json_decode($jsonReferrals);
        $user_id = $_GET['id'];

        for ($i = 0; $i < count($arrayReferrals); $i++) {
            $referred = $arrayReferrals[$i];
            $_GET['id'] = $referred->referred_id;

            if ($_GET['id'] == $_GET['id_2']) {
                unset($arrayReferrals[$i]);

                $jsonReferrals = json_encode($arrayReferrals);
                ReferralsQuerys::update('user_id', $user_id, $jsonReferrals);
            }
        }

        JsonResponse::removed('referred');
    }

    // private static function generateReferredData()
    // {
    //     $query = DataQuerys::select('user_id, username, image, phone');
    //     $objData = GeneralMethods::processById($query);

    //     if ($objData) {
    //         $objData->create_date = date('Y-m-d h:i:s');
    //     } else throw new Exception('Referred does not exist', 400);

    //     return $objData;
    // }

    private static function errorNotFound()
    {
        throw new Exception('not found resourse', 404);
    }
}
