<?php

include "./includes/db_conn.php";

if( isset($_POST['email']) && isset($_POST['psw1']) && isset($_POST['psw2']) ) {

    $email = $_POST['email'];
    $psw1 = $_POST['psw1'];
    $psw2 = $_POST['psw2'];

    $psw_encrypt = sha1($psw2);

    if( $psw1 == $psw2 ) {
        $query = "INSERT INTO users (email, password) VALUES ('{$email}', '{$psw_encrypt}')";

        print $query;

        if( mysqli_query($db_conn, $query) ) {
            print "Account created";
        } else {
            die("An error occurred.");
        }
    } else {
        die("Password mismatch");
    }
}

?>