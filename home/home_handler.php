<?php

include_once('../vendor/autoload.php');
\Dotenv\Dotenv::createImmutable('../')->load();

include '../includes/db_conn.php';

session_start();

if(isset($_POST['connection_id']) && isset($_POST['status']) && isset($_POST['other_user'])) {
    $conn_id = preg_replace('/[^0-9]/', '', $_POST['connection_id']);
    $status = preg_replace('/[^0-9]/', '', $_POST['status']);
    $other_user = preg_replace('/[^0-9]/', '', $_POST['other_user']);

    $sql = "UPDATE connections SET result = ";
    if($status == 1){
        $sql .= "'accepted'";
    } else {
        $sql .= "'declined'";
    }

    $sql .= " WHERE connection_id = {$conn_id} AND result = 'pending' AND (userA_id = {$_SESSION['user_id']} OR userB_id = {$_SESSION['user_id']}) LIMIT 1";
    
    $r = mysqli_query($db_conn, $sql);

    if($r){
        if(mysqli_affected_rows($db_conn) > 0){
            
            // Create new chat between users
            $csql = "INSERT INTO chats (userA_id, userB_id, A_last_viewed, B_last_viewed) VALUES ({$_SESSION['user_id']}, {$other_user}, NOW(), NOW())";
            $r2 = mysqli_query($db_conn, $csql);
            if($r2){
                if(mysqli_affected_rows($db_conn) > 0){
                    // Chat created
                    $chat_id = mysqli_insert_id($db_conn);

                    // Add messages to chat
                    $sql = "INSERT INTO messages (chat_id, user_id, timestamp, message) VALUES 
                            ({$chat_id}, {$_SESSION['user_id']}, NOW(), 'Chat Started!'),
                            ({$chat_id}, {$other_user}, NOW(), 'Chat Started!')";
                    $r3 = mysqli_query($db_conn, $sql);

                    if($r3){
                        if(mysqli_affected_rows($db_conn) > 0) {
                            echo "success";
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
            echo "failure 5";
        }
    } else {
        echo "failure 6s";
    }
    exit();
}

?>