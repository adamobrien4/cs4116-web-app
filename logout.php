<?php

include_once('./vendor/autoload.php');
\Dotenv\Dotenv::createImmutable(__DIR__)->load();

include "./includes/session_manager.php";

session_start();

session_destroy();

header("location: {$_ENV['site_home']}");

?>