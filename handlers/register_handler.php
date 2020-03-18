<?php

include_once('../vendor/autoload.php');
\Dotenv\Dotenv::createImmutable('../')->load();

include "../includes/db_conn.php";

if( isset($_POST['firstname']) && isset($_POST['lastname']) && isset($_POST['email']) && isset($_POST['psw1']) && isset($_POST['psw2']) ) {

    $fn = addslashes($_POST['firstname']);
    $ln = addslashes($_POST['lastname']);
    $email = $_POST['email'];
    $psw1 = $_POST['psw1'];
    $psw2 = $_POST['psw2'];

    $psw_encrypt = sha1($psw2);

    if( $psw1 == $psw2 ) {
        $query = "INSERT INTO users (firstname, lastname, email, password) VALUES ('{$fn}', '{$ln}', '{$email}', '{$psw_encrypt}')";

        if( mysqli_query($db_conn, $query) ) {
            print "Account created";
            header("location: {$_ENV['site_home']}");
        } else {
            die("An error occurred.");
        }
    } else {
        die("Passwords do not match");
    }
} else {
    die("Required data not found");
}

?>