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

function get_available_interests($db_conn) {
    $r = array();
    $query = "SELECT interest_id, name, icon FROM available_interests";

    $res = mysqli_query($db_conn, $query);

    if( mysqli_num_rows($res) > 0 ){
        while( $row = mysqli_fetch_assoc($res) ) {
            $r[$row['interest_id']] = array('name' => $row['name'], 'icon' => $row['icon']);
        }
        $res->free();
        return $r;
    } else {
        return false;
    }
}

// Gets a list of all the interests for this user
function get_user_interests($db_conn, $user_id) {
    $r = array();
    $query = "SELECT interest_id, rank FROM interests WHERE user_id = {$_SESSION['user_id']}";
    $res = mysqli_query($db_conn, $query);

    if( mysqli_num_rows($res) > 0 ) {
        while( $row = mysqli_fetch_assoc($res) ) {
            array_push($r, $row['interest_id']);
        }
        $res->free(); 
        return $r;
    } else {
        return false;
    }
}

// Returns whether the users profile is completed or not
function check_profile_status($db_conn, $user_id) {
    $query = "SELECT user_id FROM profiles WHERE user_id = {$user_id} AND completed = 1 LIMIT 1";

    $res = mysqli_query($db_conn, $query);

    if( mysqli_num_rows($res) > 0 ) {
        // Found data
        $res->free();
        return TRUE;
    } else {
        // No data found
        return FALSE;
    }
}

?>