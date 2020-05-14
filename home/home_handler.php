<?php

include_once('../vendor/autoload.php');
\Dotenv\Dotenv::createImmutable('../')->load();

include '../includes/db_conn.php';

session_start();

if (isset($_POST['connection_id']) && isset($_POST['status']) && isset($_POST['other_user']) && isset($_POST['is_request'])) {
    $conn_id = preg_replace('/[^0-9]/', '', $_POST['connection_id']);
    $status = preg_replace('/[^0-9]/', '', $_POST['status']);
    $other_user = preg_replace('/[^0-9]/', '', $_POST['other_user']);
    $is_request = $_POST['is_request'];

    if ($is_request == "true") {
        $sql = "UPDATE connections SET result = ";
        if ($status == 1) {
            $sql .= "'accepted'";
        } else {
            $sql .= "'declined'";
        }

        $sql .= " WHERE connection_id = {$conn_id} AND result = 'pending' AND (userA_id = {$_SESSION['user_id']} OR userB_id = {$_SESSION['user_id']}) LIMIT 1";

        $r = mysqli_query($db_conn, $sql);

        if ($r) {
            if (mysqli_affected_rows($db_conn) > 0) {

                // Delete any potential matches that were generated between the users
                $p_q = "DELETE FROM potential_matches WHERE (userA_id = {$_SESSION['user_id']} AND userB_id = {$other_user}) OR (userB_id = {$_SESSION['user_id']} AND userA_id = {$other_user})";
                $p_r = mysqli_query($db_conn, $p_q);

                // Check if user declined request
                if ($status == 1) {
                    // Create new chat between users
                    $csql = "INSERT INTO chats (userA_id, userB_id, A_last_viewed, B_last_viewed) VALUES ({$_SESSION['user_id']}, {$other_user}, NOW(), NOW())";
                    $r2 = mysqli_query($db_conn, $csql);
                    if ($r2) {
                        if (mysqli_affected_rows($db_conn) > 0) {
                            // Chat created
                            $chat_id = mysqli_insert_id($db_conn);

                            // Add messages to chat
                            $sql = "INSERT INTO messages (chat_id, user_id, timestamp, message) VALUES 
                            ({$chat_id}, {$_SESSION['user_id']}, NOW(), 'Chat Started!'),
                            ({$chat_id}, {$other_user}, NOW(), 'Chat Started!')";
                            $r3 = mysqli_query($db_conn, $sql);

                            if ($r3) {
                                if (mysqli_affected_rows($db_conn) > 0) {
                                    echo "success_c";
                                } else {
                                    echo "failure 1";
                                }
                            } else {
                                echo "failure 2";
                            }
                        } else {
                            echo "failure 3";
                        }
                    } else {
                        echo "failure 4";
                    }
                } else {
                    echo "success_ca";
                }
            } else {
                echo "failure 5";
            }
        } else {
            echo "failure 6s";
        }
    } else {
        $sql = "DELETE FROM potential_matches WHERE id = {$conn_id} LIMIT 1";
        $query = mysqli_query($db_conn, $sql);

        if ($query) {
            if (mysqli_affected_rows($db_conn) > 0) {

                if ($status == 1) {
                    $c_sql = "INSERT INTO connections (userA_id, userB_id, timestamp, result) VALUES ({$_SESSION['user_id']}, {$other_user}, NOW(), 'pending')";
                    $c_q = mysqli_query($db_conn, $c_sql);

                    if ($c_q) {
                        if (mysqli_affected_rows($db_conn) > 0) {
                            echo "success_p";
                        } else {
                            echo "failure 1a";
                        }
                    } else {
                        echo "failure 2a";
                    }
                } else {
                    echo "success_pa";
                }
            } else {
                echo "failure 3a";
            }
        } else {
            echo "failure 4a";
        }
    }

    exit();
}
