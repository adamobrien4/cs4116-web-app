<?php

session_start();

include_once('../vendor/autoload.php');
\Dotenv\Dotenv::createImmutable('../')->load();

include "../includes/db_conn.php";
include "../includes/helper_functions.php";
include "../includes/admin_helper_functions.php";

if (isset($_POST['interests'])) {
    $interests = $_POST['interests'];

    if (count($interests) > 5) {
        die("error: too many interests");
    }

    $d_query = "DELETE FROM interests WHERE user_id = {$_SESSION['user_id']}";

    if (mysqli_query($db_conn, $d_query)) {
        // Successful delete
        $query = "INSERT INTO interests (user_id, interest_id, rank) VALUES ";

        foreach ($interests as $k => $el) {
            $query .= "({$_SESSION['user_id']}, {$el}, {$k}),";
        }

        $query = rtrim($query, ',');

        mysqli_query($db_conn, $query);

        if (mysqli_affected_rows($db_conn) > 0) {
            // Successful insert
            complete_profile_check($db_conn);
            die("ok");
        } else {
            die("error: not inserted");
        }
    }
}

if (isset($_POST['traits'])) {
    $traits = $_POST['traits'];

    if (count($traits) > 5) {
        die("error: too many traits");
    }

    $d_query = "DELETE FROM traits WHERE user_id = {$_SESSION['user_id']}";

    if (mysqli_query($db_conn, $d_query)) {
        // Successful delete
        $query = "INSERT INTO traits (user_id, trait_id) VALUES ";

        foreach ($traits as $el) {
            $query .= "({$_SESSION['user_id']}, {$el}),";
        }

        $query = rtrim($query, ',');

        mysqli_query($db_conn, $query);

        if (mysqli_affected_rows($db_conn) > 0) {
            // Successful insert
            complete_profile_check($db_conn);
            die("ok");
        } else {
            die("error: not inserted");
        }
    }
}

if (isset($_POST['firstname']) && isset($_POST['lastname']) && isset($_POST['age']) && isset($_POST['gender']) && isset($_POST['seeking']) && isset($_POST['description'])) {

    $firstname = preg_replace("/[^a-zA-Z]+/", "", $_POST['firstname']);
    $lastname = preg_replace("/[^a-zA-Z]+/", "", $_POST['lastname']);
    $age = preg_replace("/[^0-9]+/", "", $_POST['age']);
    $gender = preg_replace("/[^a-z]+/", "", $_POST['gender']);
    $seeking = preg_replace("/[^a-z]+/", "", $_POST['seeking']);
    $description = addslashes(preg_replace("/[^a-zA-Z0-9 -,.']+/", "", $_POST['description']));

    $user_data = get_profile_data($db_conn, $_SESSION['user_id']);

    if ($user_data) {

        $fn_check = $firstname != $user_data['firstname'];
        $ln_check = $lastname != $user_data['lastname'];
        $age_check = $age != $user_data['age'];
        $gender_check = $gender != $user_data['gender'];
        $seeking_check = $seeking != $user_data['seeking'];
        $desc_check = $description != $user_data['description'];

        if ($fn_check || $ln_check) {

            $acc_query = "UPDATE users SET ";
            if ($fn_check) {
                $acc_query .= "firstname = '{$firstname}',";
            }
            if ($ln_check) {
                $acc_query .= "lastname = '{$lastname}',";
            }

            $acc_query = rtrim($acc_query, ',') . " WHERE user_id = {$_SESSION['user_id']}";


            mysqli_query($db_conn, $acc_query);

            if (mysqli_affected_rows($db_conn) > 0) {
                // echo "User account updated";
            } else {
                die("User account not updated");
            }
        }

        if ($age_check || $gender_check || $seeking_check || $desc_check) {
            $query = "UPDATE profiles SET ";

            if ($age_check) {
                $query .= "age = {$age},";
            }
            if ($gender_check) {
                $query .= "gender = '{$gender}',";
            }
            if ($seeking_check) {
                $query .= "seeking = '{$seeking}',";
            }
            if ($desc_check) {
                $query .= "description = '{$description}',";
            }

            $query = rtrim($query, ',') . " WHERE user_id = {$_SESSION['user_id']}";
            mysqli_query($db_conn, $query);

            if (mysqli_affected_rows($db_conn)) {
                // echo "User profile updated";
            } else {
                die("User profile not updated");
            }
        }

        complete_profile_check($db_conn);

        header("location: {$_ENV['site_home']}profile");
        die();
    }
}

function complete_profile_check($db_conn)
{
    // Check to see if the user is marked as complete
    if ($_SESSION['completed'] == 0) {
        // Check to see if their account is completed now

        // At least one interest
        $i_count = 0;
        $q = "SELECT interest_id FROM interests WHERE user_id = {$_SESSION['user_id']}";
        $r = mysqli_query($db_conn, $q);

        if ($r) {
            $i_count = mysqli_num_rows($r);
        }

        // At least one trait
        $t_count = 0;
        $q = "SELECT trait_id FROM traits WHERE user_id = {$_SESSION['user_id']}";
        $r = mysqli_query($db_conn, $q);

        if ($r) {
            $t_count = mysqli_num_rows($r);
        }

        // gender, seeking, description, age
        $query = "SELECT gender, seeking, description, age FROM profiles WHERE user_id = {$_SESSION['user_id']}";
        $res = mysqli_query($db_conn, $query);

        if ($res) {
            if (mysqli_num_rows($res) > 0) {
                $row = mysqli_fetch_assoc($res);
                if (($row['gender'] == "male" || $row['gender'] == "female") && ($row['seeking'] == "male" || $row['seeking'] == "female") && strlen($row['description']) > 0 && $row['age'] > 0 && $i_count > 0 && $t_count > 0) {
                    $query = "UPDATE profiles SET completed = 1 WHERE user_id = {$_SESSION['user_id']}";
                    $res = mysqli_query($db_conn, $query);

                    if ($res) {
                        if (mysqli_affected_rows($db_conn) > 0) {
                            // Updated
                            $_SESSION['completed'] = 1;

                            // Check whether the user needs more potential matches
                            $c_sql = "SELECT IF(TABLE2.userA_id = {$_SESSION['user_id']}, TABLE2.userB_id, TABLE2.userA_id) AS other_user_id, TABLE2.id FROM ( SELECT id, userA_id, userB_id FROM potential_matches WHERE (userA_id = {$_SESSION['user_id']} OR userB_id = {$_SESSION['user_id']}) ) AS TABLE2";
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
                    }
                }
            }
        }
    }
}
