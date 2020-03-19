<?php

include_once('./vendor/autoload.php');
\Dotenv\Dotenv::createImmutable(__DIR__)->load();

include "./includes/session_manager.php";

session_start();

logout();

print "<h2>You are logged out!</h2>";
print "<p><a href='{$_ENV['site_home']}'>Home</a></p>";

?>