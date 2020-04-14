<?php

include_once('./vendor/autoload.php');
\Dotenv\Dotenv::createImmutable(__DIR__)->load();

include('./includes/db_conn.php');

$sql = "SELECT user_id, email FROM users";

$r = mysqli_query($db_conn, $sql);

if($r){
    if(mysqli_num_rows($r)>0){
        while($row = mysqli_fetch_assoc($r)){
            $img_url = "https://api.adorable.io/avatars/300/" . $row['email'];
            $file_loc = "./assets/uploads/{$row['user_id']}.jpg";
            if( file_put_contents($file_loc, file_get_contents($img_url)) ){
                echo "<h6>" . $row['email'] . " success</h6>";
            } else {
                echo "<h6>" . $row['email'] . " failure</h6>";
            }
        }
    }
}