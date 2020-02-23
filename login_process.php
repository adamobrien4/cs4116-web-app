<?php

include "./includes/db_conn.php";
include "./includes/login_check.php";

// Allow only logged out users to visit this page
login_check(0);

if( isset($_POST['email']) && isset($_POST['password']) ) {

    $email = $_POST['email'];
    $psw = $_POST['password'];

    $psw_encrypt = sha1($psw);

    $query = "Select email, password FROM users WHERE email = '" . $_POST['email'] . "' AND password = '" . $psw_encrypt . "'";

    if( $res = mysqli_query($db_conn, $query) ) {
        // Found user
        $user = mysqli_fetch_assoc($res);

        // Set SESSION variables
        $_SESSION['email'] = $user['email'];
        $_SESSION['id'] = $user['id'];

        header("localhost/cs4116/profile.php");
    }
}

?>