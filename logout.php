<?php
ob_start();
session_start();
include_once 'includes/class.Main.php';
//Object initialization
$dbf = new Main();


//Delete data from temp login table
/*$yesterday=date('Y-m-d', strtotime("-2 day"));
$del_string="login_date <= '$yesterday'";
$dbf->deleteFromTable("temp_login",$del_string);*/

$dbf->userLogout();
header('Location:.');exit;
?>