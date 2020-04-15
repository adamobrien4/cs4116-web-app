<?php

include_once('vendor/autoload.php');
\Dotenv\Dotenv::createImmutable(__DIR__)->load();

session_start();

if(isset($_SESSION['user_id'])){
    header("Location:{$_ENV['site_home']}home");
} else {
    header("Location:{$_ENV['site_home']}login.php");
}
exit();

?>