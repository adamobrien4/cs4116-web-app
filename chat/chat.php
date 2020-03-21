<? php

    include_once('../vendor/autoload.php');
    \Dotenv\Dotenv::createImmutable('../')->load();

    include '../includes/db_conn.php';
    include '../includes/login_check.php';
    include '../includes/helper_functions.php';

    login_check(1);




?>