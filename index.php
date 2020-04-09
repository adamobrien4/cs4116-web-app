<?php

include_once('vendor/autoload.php');
\Dotenv\Dotenv::createImmutable(__DIR__)->load();

header("Location:{$_ENV['site_home']}home");
exit();

?>