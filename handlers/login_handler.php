<?php

include_once('../vendor/autoload.php');
\Dotenv\Dotenv::createImmutable('../')->load();

include '../includes/db_conn.php';
include '../includes/login_check.php';
include '../includes/admin_helper_functions.php';

// Allow only logged out users to visit this page
login_check(0);

if (isset($_POST['email']) && isset($_POST['password'])) {

    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $psw = $_POST['password'];

    $psw_encrypt = sha1($psw);

    $query = "SELECT users.user_id, users.email, users.firstname, users.lastname, users.user_id, users.admin, profiles.completed, profiles.banned FROM users INNER JOIN profiles ON users.user_id = profiles.user_id WHERE users.email = '{$_POST['email']}' AND users.password = '{$psw_encrypt}'";

    $res = mysqli_query($db_conn, $query);

    if (mysqli_num_rows($res) > 0) {
        // Found user
        $user = mysqli_fetch_assoc($res);

        if ($user['banned'] == true) {
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

        if ($user['completed'] == 1) {
            // Check whether the user needs more potential matches
            $c_sql = "SELECT IF(TABLE2.userA_id = {$user['user_id']}, TABLE2.userB_id, TABLE2.userA_id) AS other_user_id, TABLE2.id FROM ( SELECT id, userA_id, userB_id FROM potential_matches WHERE (userA_id = {$user['user_id']} OR userB_id = {$user['user_id']}) ) AS TABLE2";
            $c_query = mysqli_query($db_conn, $c_sql);

            if ($c_query) {
                if (mysqli_num_rows($c_query) > 0) {
                    // User has remaining potential_matches
                } else {
                    // User has no remaining potential matches
                    generate_possible_connections($db_conn);
                }
            }
        }

        header("location: {$_ENV['site_home']}profile/");
    } else {
        header("location: {$_ENV['site_home']}login.php?n=creds_incorrect");
    }
} else {
    header("location: {$_ENV['site_home']}login.php?n=login_error");
}
