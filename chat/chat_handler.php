<?php

class Conversation
{
    var $matches_id;
    var $chat_id;
    var $matches_name;
    var $matches_photo;
    var $last_accessed;

    public function __construct($chat_id, $matches_name){
        $this->chat_id = $chat_id;
        $this->matches_name = $matches_name;
    }

    function basic ($chat_id, $matches_name)
    {
        $this->chat_id = $chat_id;
        $this->matches_name = $matches_name;
        //$this->matches_photo = $photo;
        //$this->last_accessed = $time;
    }

    function getName(){
        return $this->matches_name;
    }


    function getId(){
        return $this->matches_id;
    }

    function toString(){
        return ' ' . $this->chat_id . ' . ' . $this->matches_name . ' .';
    }
}


class Message {
    var $timestamp;
    var $message_id;
    var $message;
    var $sender; //boolean toggle to check if the person is the sender or receiver


    public function __construct($message_id, $timestamp, $message, $user_id)
    {
    $this->message_id = $message_id;
    $this->timestamp = $timestamp;
    $this->message = $message;
    if ($user_id == $_SESSION['user_id']){
        $this->sender = True;
    } else{
        $this->sender = False;
    }
    }
}




include_once('../vendor/autoload.php');
\Dotenv\Dotenv::createImmutable('../')->load();

include "../includes/db_conn.php";

/*
select chats.chat_id, users.firstname from chats inner join users on chats.userA_id = users.user_id or chats.userB_id = users.user_id where users.user_id != 19
select message_id, timestamp, message from messages where chat_id = 1
*/
//session_start();//allows me to use session data on this page - doesnt create a new session tho
//dont need it anymore as this page is being included somewhere else
$query = "select chats.chat_id, users.firstname from chats inner join users on chats.userA_id = users.user_id or chats.userB_id = users.user_id where users.user_id != " . $_SESSION['user_id'];



if (! $db_conn){
    echo "Database Connection Failed";
}

$res = mysqli_query($db_conn, $query);

if(! $res){ //handles errors if the query failed
    echo mysqli_error();
}

$chats = array();

if (mysqli_num_rows($res) == 0){ //this means the user has no chats
    echo "You have no conversations yet loser! Get matching";
} else{
    while($row = mysqli_fetch_assoc($res)){
        echo "<p></p>";
        array_push($chats, new Conversation($row["chat_id"], $row["firstname"]));
    }
}


//echo ("<p>Now lets print out our array of conversations</p>");

/*
foreach ($chats as $item){
    var_dump($item);
}*/
//var_dump($chats); //nakes it a bit easier to understand




//echo "<p>Here we have all the conversations we are apart of and the person matched with</p><p>Now lets try and focus on a specific conversation with adam</p>";




//$query_messages = "select message_id, timestamp, message from messages where chat_id = " . $chats[0]->chat_id ; //always get the first conversation
//function to call messages
function messageCall($db_conn, $chat_id){
    $messages = array();
    $query_messages = "select message_id, timestamp, user_id, message from messages where chat_id = " . $chat_id ;
    $res = mysqli_query($db_conn, $query_messages);
    if(! $res){ //handles errors if the query failed
        echo mysqli_error();
    }

    if (mysqli_num_rows($res) == 0){ //this means the user has no chats
        echo "You have no conversations yet loser! Get matching";
        //RETURN SOMETHING HERE TOO !!
    } else{
        while($row = mysqli_fetch_assoc($res)){
            echo "<p></p>";
            array_push($messages, new Message($row["message_id"], $row["timestamp"], $row["message"], $row["user_id"]));
        }
    }
    return $messages;
}

$mess = messageCall($db_conn, $chats[0]->chat_id); //call the first conversation
//var_dump($mess);




//get all the data from each row and add to an array of objects

// when it comes down to displaying messages then do a new query of the messages 




?>