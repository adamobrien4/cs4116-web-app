<?php

include "./global_vars.php";

$db_conn = mysqli_connect($db_host, $db_user, $db_password, $db_name);

if( mysqli_connect_errno() ) {
    die("Failed to connect to DB");
}

?>