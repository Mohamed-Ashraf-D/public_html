<?php
session_start(); //to start session

session_unset();// to unset the data
session_destroy(); // to destroy session
//unset remember me cookies
$days = 30;
setcookie ("rememberme","", time() - ($days *  24 * 60 * 60 * 1000) );

header('Location:index.php'); //to redirect to login page
exit();
?>