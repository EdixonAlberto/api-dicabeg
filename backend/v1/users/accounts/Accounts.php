<?php

require_once 'AccountsQuerys.php';

class Accounts
{
    public static function getAccountsAlls()
    {
        $users = AccountsQuerys::selectAlls();
        if ($users) JsonResponse::read('usersAccounts', $users);
        else self::errorNotFound();
    }

    public static function getAccountsById()
    {
        $user = AccountsQuerys::selectById();
        if ($user) JsonResponse::read('userAccount', $user);
        else self::errorNotFound();
    }

    public static function createAccount()
    {
        $user = AccountsQuerys::select('email', $_REQUEST['email']);
        if (!$user) {
            $_GET['id'] = Gui::generate();

            $asReferred = isset($_REQUEST['invite-code']);
            if ($asReferred) {
                Referrals::createReferrals();
                $info = 'referred added';
            } else $info = null;

            $arrayAccount[] = $_REQUEST['email'];
            $arrayAccount[] = Security::encryptPassword();
            AccountsQuerys::insert($arrayAccount);

            $email = $_REQUEST['email'];
            $lengh = strpos($email, '@');
            $username = substr($email, 0, $lengh);
            DataQuerys::insert($username);

            ReferralsQuerys::insert();

            $account = AccountsQuerys::selectById();
            $path = 'https://' . $_SERVER['SERVER_NAME'] . '/v1/sessions/';

            JsonResponse::created('userAccount', $account, $path, $info);

        } else throw new Exception('email exist', 400);
    }

    public static function updateAccount()
    {
        $user = AccountsQuerys::selectById();
        if ($user) {
            foreach ($_REQUEST as $key => $value) {
                $value = ($key == 'password') ?
                    Security::encryptPassword() :
                    $value;
            }
            AccountsQuerys::update($key, $value);

            $user = AccountsQuerys::selectById();
            JsonResponse::updated('userAccount', $user);

        } else self::errorNotFound();
    }

    public static function deleteAccount()
    {
        $user = AccountsQuerys::selectById();
        if ($user) {
            SessionsQuerys::delete();
            ReferralsQuerys::delete();
            DataQuerys::delete();
            AccountsQuerys::delete();

            JsonResponse::removed('userAccount');

        } else self::errorNotFound();
    }

    private static function errorNotFound()
    {
        throw new Exception('not found resourse', 404);
    }
}
