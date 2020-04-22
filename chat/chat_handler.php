<?php

include_once('../vendor/autoload.php');
\Dotenv\Dotenv::createImmutable('../')->load();

include "../includes/db_conn.php";

session_start();

/*
select chats.chat_id, users.firstname from chats inner join users on chats.userA_id = users.user_id or chats.userB_id = users.user_id where users.user_id != 19
select message_id, timestamp, message from messages where chat_id = 1
*/
//session_start();//allows me to use session data on this page - doesnt create a new session tho
//dont need it anymore as this page is being included somewhere else


if( isset($_POST['chat_id_request']) && isset($_POST['messageTimestamp']) && isset($_POST['user_type']) ) {
    $chat_id = preg_replace('/[^0-9]/', '', $_POST['chat_id_request']);
    $tstmp = preg_replace('/[^0-9 -:]/', '', $_POST['messageTimestamp']);

    $sql = "SELECT timestamp FROM messages WHERE chat_id = {$chat_id} ORDER BY timestamp DESC LIMIT 1";

    $r = mysqli_query($db_conn, $sql);

    if($r){
        if(mysqli_num_rows($r) > 0) {
            $row = mysqli_fetch_assoc($r);

            if($row['timestamp'] == $tstmp){
                echo "no_new";
            } else {
                echo getChatMessages($db_conn, $chat_id, $_POST['user_type']);
            }
        }
    }
    exit();
}

if(isset($_POST['chat_id_request']) && isset($_POST['user_type'])) {
    echo getChatMessages($db_conn, $_POST['chat_id_request'], $_POST['user_type']);
    exit();
}

if(isset($_POST['message']) && isset($_POST['chat_id'])){
    // Add new message

    $m = addslashes($_POST['message']);
    $cid = preg_replace('/[^0-9]/', '', $_POST['chat_id']);

    $sql = "INSERT INTO messages (chat_id, user_id, timestamp, message) VALUES ({$cid}, {$_SESSION['user_id']}, NOW(), '{$m}')";

    $r = mysqli_query($db_conn, $sql);
    if($r){
        if(mysqli_affected_rows($db_conn) > 0) {
            echo "success";
        } else {
            echo "failure";
        }
    } else {
        echo "failure";
    }
    exit();
}

function getChatMessages($db_conn, $chat_id_param, $user_type_param) {
    $chat_id = preg_replace('/[^0-9]/', '', $chat_id_param);
    $user_type = preg_replace('/[^a-z]/i', '', $user_type_param);

    $messages = array();

    // Update last time this user viewed this chat

    if($user_type != "A" && $user_type != "B"){
        die("no_user_type_set");
    }

    $sql = "UPDATE chats SET ";
    if($user_type == "A"){
        $sql .= "A_last_viewed = ";
    } else {
        $sql .= "B_last_viewed = ";
    }
    $sql .= "NOW() WHERE chat_id = {$chat_id}";
    mysqli_query($db_conn, $sql);

    $query_messages = "SELECT message_id, timestamp, user_id, message FROM messages WHERE chat_id = {$chat_id}";
    $res = mysqli_query($db_conn, $query_messages);
    if($res) { //handles errors if the query failed
        if (mysqli_num_rows($res) > 0){ //this means the user has no chats
            while($row = mysqli_fetch_assoc($res)){
                array_push($messages, $row);
            }
        }
    }

    if(count($messages) > 0){
        return json_encode($messages);
    } else {
        return "empty";
    }
}