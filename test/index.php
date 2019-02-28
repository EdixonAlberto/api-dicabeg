<?php
date_default_timezone_set('America/Caracas');
echo date('Y-d-m h:i', 1550668680);

die;


date_default_timezone_set('America/Caracas');
$nuevafecha = strtotime('+10 minute', strtotime(date('Y-m-d h:i')));
$nuevafecha2 = strtotime(date('y-M-D h:i') . '+10 minute');
echo date('Y-m-d h:i', $nuevafecha);
echo '<br />';
echo date('Y-m-d h:i', $nuevafecha2);
die;


$array = ['num1' => 1, 'num2' => 2, 'num3' => 3];
var_dump($array);
unset($array['num1']);
var_dump($array);
die;


$referrals = (object)[
    'referral_id' => null,
    'create_date' => date()
];

$juser = json_encode($referrals);

echo $juser;
die;
header("Access-Control-Allow-Origin: http://localhost/rest-api-authentication-example/");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$var = json_decode(file_get_contents('php://input'));
var_dump($var);

print_r($var->name);
die;
echo date("d-m-Y");
echo '<br />';
echo time();