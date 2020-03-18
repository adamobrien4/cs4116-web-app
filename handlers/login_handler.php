<?php

include_once('../vendor/autoload.php');
\Dotenv\Dotenv::createImmutable('../')->load();

include '../includes/db_conn.php';
include '../includes/login_check.php';

// Allow only logged out users to visit this page
login_check(0);

if( isset($_POST['email']) && isset($_POST['password']) ) {

    $email = $_POST['email'];
    $psw = $_POST['password'];

    $psw_encrypt = sha1($psw);

    $query = "Select user_id, email, password FROM users WHERE email = '" . $_POST['email'] . "' AND password = '" . $psw_encrypt . "'";

    $res = mysqli_query($db_conn, $query);

    if( mysqli_num_rows($res) > 0 ) {
        // Found user
        $user = mysqli_fetch_assoc($res);

        // Set SESSION variables
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['email'] = $user['email'];

        header("location: {$_ENV['site_home']}profile/");
    } else {
        header("location: {$_ENV['site_home']}login.php");
    }
} else {
    die("Required data not found");
}

?>