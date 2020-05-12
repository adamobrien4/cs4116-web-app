<?php

include_once('../vendor/autoload.php');
\Dotenv\Dotenv::createImmutable('../')->load();

include '../includes/db_conn.php';
include '../includes/login_check.php';

// Allow only logged out users to visit this page
login_check(0);

if( isset($_POST['email']) && isset($_POST['password']) ) {

    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $psw = $_POST['password'];

    $psw_encrypt = sha1($psw);

    $query = "SELECT users.email, users.firstname, users.lastname, users.user_id, users.admin, profiles.completed, profiles.banned FROM users INNER JOIN profiles ON users.user_id = profiles.user_id WHERE users.email = '{$_POST['email']}' AND users.password = '{$psw_encrypt}'";

    $res = mysqli_query($db_conn, $query);

    if( mysqli_num_rows($res) > 0 ) {
        // Found user
        $user = mysqli_fetch_assoc($res);

        if($user['banned'] == true) {
            // This user is banned
            header("location: {$_ENV['site_home']}login.php?n=account_banned");
            exit();
        }

        // Set SESSION variables
        $_SESSION['fullname'] = $user['firstname'] . ' ' . $user['lastname'];
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['admin'] = $user['admin'];
        $_SESSION['completed'] = $user['completed'];

        header("location: {$_ENV['site_home']}profile/");
    } else {
        header("location: {$_ENV['site_home']}login.php?n=creds_incorrect");
    }
} else {
    header("location: {$_ENV['site_home']}login.php?n=login_error");
}

?>