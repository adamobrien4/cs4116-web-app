<?php

$db_conn = mysqli_connect($_ENV['db_host'], $_ENV['db_user'], $_ENV['db_password'], $_ENV['db_name']);

if( mysqli_connect_errno() ) {
    die("Failed to connect to DB");
}

?>