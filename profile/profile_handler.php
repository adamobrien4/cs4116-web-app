<?php

session_start();

include_once('../vendor/autoload.php');
\Dotenv\Dotenv::createImmutable('../')->load();

include "../includes/db_conn.php";
include "../includes/helper_functions.php";

if( isset($_POST['interests']) ) {
    $interests = $_POST['interests'];

    if( count($interests) > 5 ){
        die("error: too many interests");
    }

    $d_query = "DELETE FROM interests WHERE user_id = {$_SESSION['user_id']}";

    if( mysqli_query($db_conn, $d_query) ){
        // Successful delete
        $query = "INSERT INTO interests (user_id, interest_id, rank) VALUES ";

        foreach($interests as $k => $el) {
            $query .= "({$_SESSION['user_id']}, {$el}, {$k}),";
        }

        $query = rtrim($query, ',');

        mysqli_query($db_conn, $query);

        if( mysqli_affected_rows($db_conn) > 0 ) {
            // Successful insert
            die("ok");
        } else {
            die("error: not inserted");
        }
    }
}

if( isset($_POST['traits']) ) {
    $traits = $_POST['traits'];

    if( count($traits) > 5 ){
        die("error: too many traits");
    }

    $d_query = "DELETE FROM traits WHERE user_id = {$_SESSION['user_id']}";

    if( mysqli_query($db_conn, $d_query) ){
        // Successful delete
        $query = "INSERT INTO traits (user_id, trait_id) VALUES ";

        foreach($traits as $el) {
            $query .= "({$_SESSION['user_id']}, {$el}),";
        }

        $query = rtrim($query, ',');

        mysqli_query($db_conn, $query);

        if( mysqli_affected_rows($db_conn) > 0 ) {
            // Successful insert
            die("ok");
        } else {
            die("error: not inserted");
        }
    }
}

if( isset($_POST['firstname']) && isset($_POST['lastname']) && isset($_POST['age']) && isset($_POST['gender']) && isset($_POST['seeking']) && isset($_POST['description']) ) {

    $firstname = preg_replace("/[^a-zA-Z]+/", "", $_POST['firstname']);
    $lastname = preg_replace("/[^a-zA-Z]+/", "", $_POST['lastname']);
    $age = preg_replace("/[^0-9]+/", "", $_POST['age']);
    $gender = preg_replace("/[^a-z]+/", "", $_POST['gender']);
    $seeking = preg_replace("/[^a-z]+/", "", $_POST['seeking']);
    $description = preg_replace("/[^a-zA-Z0-9 ]+/", "", $_POST['description']);

    $user_data = get_profile_data($db_conn, $_SESSION['user_id']);

    if( $user_data ) {

        $fn_check = $firstname != $user_data['firstname'];
        $ln_check = $lastname != $user_data['lastname'];
        $age_check = $age != $user_data['age'];
        $gender_check = $gender != $user_data['gender'];
        $seeking_check = $seeking != $user_data['seeking'];
        $desc_check = $description != $user_data['description'];

        if( $fn_check || $ln_check ) {

            // TODO : I have no clue what the hell is going on here. It clears the firstname and lastname whenever you try and edit either the first or last name.

            echo "<h1>Running fn ln update</h1><br>";
            $acc_query = "UPDATE users SET ";
            if( $fn_check ) {
                $acc_query .= "firstname = '{$firstname}',";
            }
            if( $ln_check ) {
                $acc_query .= "lastname = '{$lastname}',";
            }

            $acc_query = rtrim($acc_query, ',') . " WHERE user_id = {$_SESSION['user_id']}";

            echo $acc_query . "<br>";

            mysqli_query($db_conn, $acc_query);

            if( mysqli_affected_rows($db_conn) > 0 ){
                echo "<h2>Updated account</h2><br>";
                // echo "User account updated";
            } else {
                die("User account not updated");
            }
        }

        if( $age_check || $gender_check || $seeking_check || $desc_check ) {
            $query = "UPDATE profiles SET ";

            if( $age_check ){
                $query .= "age = {$age},";
            }
            if( $gender_check ){
                $query .= "gender = '{$gender}',";
            }
            if( $seeking_check ){
                $query .= "seeking = '{$seeking}',";
            }
            if( $desc_check ){
                $query .= "description = '{$description}',";
            }

            $query = rtrim($query, ',') . " WHERE user_id = {$_SESSION['user_id']}";
            echo $query . "<br>";
            mysqli_query($db_conn, $query);

            if( mysqli_affected_rows($db_conn) ){
                // echo "User profile updated";
            } else {
                die("User profile not updated");
            }
        }
        die("Done");
        header("location: {$_ENV['site_home']}profile?status=1");
    }
}

?>