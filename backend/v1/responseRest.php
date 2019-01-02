<?php
    class responseRest {

        function __construct() {

        }

        function restInfo() {
            $arrayInfo = [
                'version API' => '1.0',
                'method' => [
                    'GET' => [
                        'href' => '?id',
                        'rel' => 'get users',
                        'value' => 'alls | id'
                    ],
                    'POST' => [
                        'href' => '/users',
                        'rel' => 'create user',
                        'value1' => 'username',
                        'value2' => 'email',
                        'value3' => 'password'
                    ]
                ]
            ];

            return json_encode($arrayInfo);
        }

        function error($error) {
            $arrayError['error'] = [];

            $arrayErrorDetail = [
                'code' => '',
                'msj' => $error
            ];

            array_push($arrayError['error'], $arrayErrorDetail);

            return json_encode($arrayError);
        }

        function prepareResponse($key, $value) {
            $arrayInfo = [
                'userData' => [

                ]
            ];
        }
    }
?>