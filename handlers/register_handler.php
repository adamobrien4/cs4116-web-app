<?php

include_once('../vendor/autoload.php');
\Dotenv\Dotenv::createImmutable('../')->load();

include "../includes/db_conn.php";

if( isset($_POST['firstname']) && isset($_POST['lastname']) && isset($_POST['email']) && isset($_POST['psw1']) && isset($_POST['psw2']) ) {

    $fn = preg_replace("/[^a-zA-Z0-9]+/", "", $_POST['firstname']);
    $ln = preg_replace("/[^a-zA-Z0-9]+/", "", $_POST['lastname']);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $psw1 = $_POST['psw1'];
    $psw2 = $_POST['psw2'];

    $psw_encrypt = sha1($psw2);

    if( $psw1 == $psw2 ) {
        $query = "INSERT INTO users (firstname, lastname, email, password) VALUES ('{$fn}', '{$ln}', '{$email}', '{$psw_encrypt}')";
        $res = mysqli_query($db_conn, $query);
        $insert_id = mysqli_insert_id($db_conn);

        if( $insert_id ) {
            $prof_query = "INSERT INTO profiles (user_id, completed) VALUES ('{$insert_id}', 0)";
            $res = mysqli_query($db_conn, $prof_query);
            $prof_insert_id = mysqli_insert_id($db_conn);

            if( $prof_insert_id ){
                print "Account created";
                header("location: {$_ENV['site_home']}login.php?n=register_success");
            } else {
                die("Profile not created");
            }
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