<?php

function get_profile_data($db_conn, $user_id) {
    $return = null;

    $query = "SELECT firstname, lastname FROM users WHERE user_id = {$user_id} LIMIT 1";

    $res = mysqli_query($db_conn, $query);

    if( mysqli_num_rows($res) > 0 ) {
        // Found data
        $return = mysqli_fetch_assoc($res);
    } else {
        // No data found
        return null;
    }


    $query = "SELECT age, gender, seeking, description, photo, completed, banned FROM profiles WHERE user_id = {$user_id} LIMIT 1";

    $res = mysqli_query($db_conn, $query);

    if( mysqli_num_rows($res) > 0 ) {
        // Found data
        return array_merge($return, mysqli_fetch_assoc($res));
    } else {
        // No data found
        return null;
    }
}

// Returns whether the users profile is completed or not
function check_profile_status($db_conn, $user_id) {
    $query = "SELECT user_id FROM profiles WHERE user_id = {$user_id} AND completed = 1 LIMIT 1";

    $res = mysqli_query($db_conn, $query);

    if( mysqli_num_rows($res) > 0 ) {
        // Found data
        return TRUE;
    } else {
        // No data found
        return FALSE;
    }
}

?>