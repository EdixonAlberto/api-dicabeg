<?php

class users {

    static function getUsersAlls($query) {
        $rows = $query->rowCount();

        if ($rows) {
            for ($i = 0; $i < $rows; $i++) {
                $arrayIndexedByColumns = $query->fetch(PDO::FETCH_ASSOC);
                $arrayResponse[] = $arrayIndexedByColumns;
            }
            http_response_code(200);
        }
        else {
            http_response_code(400);
            $arrayResponse[] = ['Error' => 'There are not users'];
        }

        return $arrayResponse;
    }

    static function getUsersById($query) {
        $existRow = $query->rowCount();

        if ($existRow) {
            $arrayIndexedByColumns = $query->fetch(PDO::FETCH_ASSOC);
            $arrayResponse[] = $arrayIndexedByColumns;
            http_response_code(200);
        }
        else {
            http_response_code(400);
            $arrayResponse[] = ['Attention' => 'User Not Exist'];

        }

        return $arrayResponse;
    }
}
?>