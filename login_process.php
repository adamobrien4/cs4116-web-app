<?php

include "./includes/db_conn.php";
include "./includes/login_check.php";

// Allow only logged out users to visit this page
login_check(0);

if( isset($_POST['email']) && isset($_POST['password']) ) {

    $email = $_POST['email'];
    $psw = $_POST['password'];

    $psw_encrypt = sha1($psw);

    $query = "SELECT user_id, email FROM users WHERE email = '{$_POST['email']}' AND password = '{$psw_encrypt}'";
    $res = mysqli_query($db_conn, $query);

    if( mysqli_num_rows($res) > 0 ) {
        // Found user
        $user = mysqli_fetch_assoc($res);

        // Set SESSION variables
        $_SESSION['email'] = $user['email'];
        $_SESSION['user_id'] = $user['user_id'];

        header("localhost/cs4116/profile.php");
    } else {
        print "User could not be found";
    }
}

?>