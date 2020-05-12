<?php

include_once('../vendor/autoload.php');
\Dotenv\Dotenv::createImmutable('../')->load();

include '../includes/db_conn.php';

if(isset($_POST['toggle_user_ban_id'])) {
    $user_id = preg_replace("/[^0-9]+/", "", $_POST['toggle_user_ban_id']);

    // Get current ban status of user
    $sql = "SELECT banned FROM profiles WHERE user_id = {$user_id}";
    $query = mysqli_query($db_conn, $sql);

    if($query) {
        if(mysqli_num_rows($query) > 0){

            $row = mysqli_fetch_assoc($query);

            $togg_sql = "UPDATE profiles SET banned = ";

            if($row['banned'] == true) {
                $togg_sql .= "0 ";
            } else {
                $togg_sql .= "1 ";
            }

            $togg_sql .= "WHERE user_id = {$user_id} LIMIT 1";

            $togg_q = mysqli_query($db_conn, $togg_sql);

            if($togg_q) {
                if(mysqli_affected_rows($db_conn) > 0) {
                    echo "success";
                    exit();
                }
            }
        }
    }
    echo "failure";
    exit();
}

echo "big gay";
exit();

?>