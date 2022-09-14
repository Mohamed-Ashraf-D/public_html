<?php
session_start(); //to start session
session_unset();// to unset the data
session_destroy(); // to destroy session
header('Location:index.php'); //to redirect to login page
exit();
?>