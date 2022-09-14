<?php
ini_set('display_errors','on');
error_reporting(E_ALL);
$sessionUser='';
if (isset($_SESSION['user'])){
    $sessionUser=$_SESSION['user'];
}
include 'admin/connect.php';
$tpl="includes/templates/"; //template directory
$css="layout/css/"; // css directory
$fonts="layout/fonts/css/"; //css fonts
$js="layout/js/";
$img="layout/img/";
$lang="includes/languages/";// languages directories
$func="includes/functions/";// function directories
$lib="Libraries/stripe-php-master/";
//include important files
include $lang. 'english.php';
include $func. 'function.php';
include $tpl . 'header.php';


