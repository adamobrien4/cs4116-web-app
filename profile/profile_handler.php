<?php

session_start();

include_once('../vendor/autoload.php');
\Dotenv\Dotenv::createImmutable('../')->load();

include "../includes/db_conn.php";
include "../includes/helper_functions.php";

if( isset($_POST['interests']) ) {
    $interests = $_POST['interests'];

    $d_query = "DELETE FROM interests WHERE user_id = {$_SESSION['user_id']}";

    if( mysqli_query($db_conn, $d_query) ){
        // Successful delete
        $query = "INSERT INTO interests (user_id, interest_id, rank) VALUES ";

        foreach($interests as $k => $el) {
            $query .= "({$_SESSION['user_id']}, ".$el.", ".$k."),";
        }

        $query = rtrim($query, ',');

        mysqli_query($db_conn, $query);

        if( mysqli_affected_rows($db_conn) > 0 ) {
            // Successful insert
            die("ok");
        }
    }
}

die("Nope");

$firstname = $_POST['firstname'];
$lastname = $_POST['lastname'];
$age = $_POST['age'];
$gender = $_POST['gender'];
$seeking = $_POST['seeking'];
$description = $_POST['description'];

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
        

        $f = "UPDATE users SET firstname = 'Adam',lastname = 'OBrien' WHERE user_id = 8";
        echo $f . "<br>";
        echo $acc_query . "<br>";

        mysqli_query($db_conn, $acc_query);

        if( mysqli_affected_rows($db_conn) > 0 ){
            echo "<h2>Updated account</h2><br>";
            // echo "User account updated";
        } else {
            die("User account not updated");
        }
    }

    die("Done");

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

    //header("location: {$_ENV['site_home']}profile?status=1");
    echo "Done!";
}

?>