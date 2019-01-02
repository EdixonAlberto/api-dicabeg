<?php
include '../../dataBase.php';
include 'querysAccount.php';
include '../data/querysData.php';
include 'account.php';
include '../../responseRest.php';
include '../../security.php';


$query = new querysAccount();
$account = new account();
$security = new security();


?>