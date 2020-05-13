<?php

include_once('../vendor/autoload.php');
\Dotenv\Dotenv::createImmutable('../')->load();

include '../includes/db_conn.php';

if (isset($_POST['toggle_user_ban_id'])) {
    $user_id = preg_replace("/[^0-9]+/", "", $_POST['toggle_user_ban_id']);

    // Get current ban status of user
    $sql = "SELECT banned FROM profiles WHERE user_id = {$user_id}";
    $query = mysqli_query($db_conn, $sql);

    if ($query) {
        if (mysqli_num_rows($query) > 0) {

            $row = mysqli_fetch_assoc($query);

            $togg_sql = "UPDATE profiles SET banned = ";

            if ($row['banned'] == true) {
                $togg_sql .= "0 ";
            } else {
                $togg_sql .= "1 ";
            }

            $togg_sql .= "WHERE user_id = {$user_id} LIMIT 1";

            $togg_q = mysqli_query($db_conn, $togg_sql);

            if ($togg_q) {
                if (mysqli_affected_rows($db_conn) > 0) {
                    echo $row['banned'] . "_success";
                    exit();
                }
            }
        }
    }
    echo "failure";
    exit();
}

if (isset($_POST['delete_user']) && isset($_POST['user_email'])) {
    $user_id = preg_replace("/[^0-9]+/", "", $_POST['delete_user']);
    $email = $_POST['user_email'];

    $sql = "DELETE FROM users WHERE user_id = {$user_id} LIMIT 1";
    $query = mysqli_query($db_conn, $sql);

    if ($query) {
        if (mysqli_affected_rows($db_conn) > 0) {

            $sql = "DELETE FROM profiles WHERE user_id = {$user_id} LIMIT 1";
            $query = mysqli_query($db_conn, $sql);

            if ($query) {
                if (mysqli_affected_rows($db_conn) > 0) {

                    $sql = "INSERT INTO blacklist (email, timestamp) VALUES ('{$email}', NOW())";
                    $query = mysqli_query($db_conn, $sql);

                    if($query) {
                        if(mysqli_affected_rows($db_conn) > 0) {
                            echo "success";
                            exit();
                        }
                    }
                }
            }
        }
    }
    echo "failure";
    exit();
}

if (isset($_POST['firstname']) && isset($_POST['lastname']) && isset($_POST['description']) && isset($_POST['user_id'])) {
    $firstname = preg_replace("/[^a-zA-Z]+/", "", $_POST['firstname']);
    $lastname = preg_replace("/[^a-zA-Z]+/", "", $_POST['lastname']);
    $description = addslashes(preg_replace("/[^a-zA-Z0-9 -,.']+/", "", $_POST['description']));
    $user_id = preg_replace("/[^0-9]+/", "", $_POST['user_id']);

    $sql = "UPDATE profiles SET description = '{$description}' WHERE user_id = {$user_id} LIMIT 1";
    $query = mysqli_query($db_conn, $sql);

    $sql = "UPDATE users SET firstname = '{$firstname}', lastname = '{$lastname}' WHERE user_id = {$user_id} LIMIT 1";
    $query = mysqli_query($db_conn, $sql);
    echo "success";
    exit();
}

exit();
