<?php
include 'connect.php';
$tpl="includes/templates/"; //template directory
$css="layout/css/"; // css directory
$fonts="layout/fonts/css/"; //css fonts
$js="layout/js/";
$img="layout/img/";
$lang="includes/languages/";// languages directories
$func="includes/functions/";// function directories
//include important files
include $lang. 'english.php';
include $func. 'function.php';
include $tpl . 'header.php';
//include navbar to all pages except the one contains noNavbar
if (!isset($noNavbar)){
    include $tpl.'navbar.php';
}


?>