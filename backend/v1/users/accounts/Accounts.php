<?php

require_once '../../tools/Gui.php';
require_once '../../tools/GeneralMethods.php';
require_once '../data/Data.php';
require_once 'AccountsQuerys.php';

class Accounts extends AccountsQuerys
{
    public static function getAccountsAlls()
    {
        $query = self::selectAlls();
        return GeneralMethods::processAlls($query);
    }

    public static function getAccountsById()
    {
        $query = self::selectById($_GET['id']);
        return GeneralMethods::processById($query);
    }

    public static function insertAccount()
    {
        $existingUser = self::checkout();
        if (!$existingUser) {
            $_GET['id'] = Gui::generate();
            foreach ($_REQUEST as $value) {
                $arrayAccount[] = $value;
            }
            $arrayAccount[] = null;
            $arrayAccount[] = null;
            // TODO: $arrayAccount[] = TimeStamp::timeGet();c

            $result = self::insert($arrayAccount);
            self::interpretResult($result);
            Data::insertData();

            $arrayResponse = [
                'Successful' => 'Created Users',
                'id' => $_GET['id']
            ];
        } else throw new Exception('User already exists', 400);

        return $arrayResponse;
    }

    public static function updateAccount()
    {
        $existingUser = self::checkout();
        if ($existingUser) {
            foreach ($_REQUEST as $key => $value) {
                $column = $key;
                $arrayAccount[] = $value;
            }
            $arrayAccount[] = null;
            // TODO: $arrayAccount[] = TimeStamp::timeGet();

            $result = self::update($column, $arrayAccount);
            self::interpretResult($result);

            $arrayResponse = ['Successful' => 'Updated user account'];
        } else throw new Exception('User not exist', 400);

        return $arrayResponse;
    }

    public static function deleteAccount()
    {
        $existingUser = self::checkout();
        if ($existingUser) {
            $result = DataQuerys::delete();
            self::interpretResult($result);

            $result = self::delete();
            self::interpretResult($result);

            $arrayResponse = ['Successful' => 'Deleted user account'];
        } else throw new Exception('User does not exist', 400);

        return $arrayResponse;
    }

    private function interpretResult($result)
    {
        $error = $result->errorInfo();
        $errorExist = !is_null($error[1]);
        if ($errorExist) {
            throw new Exception($error[2], 400);
        }
    }

    private function checkout()
    {
        $result = self::selectById($_GET['id']);
        $rows = $result->rowCount();
        return $rows ? true : false;
    }
}
