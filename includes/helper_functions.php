<?php

function get_profile_data($db_conn, $user_id) {
    $return = null;

    $query = "SELECT firstname, lastname FROM users WHERE user_id = {$user_id} LIMIT 1";

    $res = mysqli_query($db_conn, $query);

    if($res){
        if( mysqli_num_rows($res) > 0 ) {
            // Found data
            $return = mysqli_fetch_assoc($res);
        } else {
            // No data found
            return null;
        }
    }


    $query = "SELECT age, gender, seeking, description, completed, banned FROM profiles WHERE user_id = {$user_id} LIMIT 1";

    $res = mysqli_query($db_conn, $query);

    if($res){
        if( mysqli_num_rows($res) > 0 ) {
            // Found data
            return array_merge($return, mysqli_fetch_assoc($res));
        } else {
            // No data found
            return null;
        }
    }
    return null;
}

// Gets a list of all available interests from the database
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

// Gets a list of available traits from the database
function get_available_traits($db_conn) {
    $r = array();
    $query = "SELECT trait_id, name, icon FROM available_traits";

    $res = mysqli_query($db_conn, $query);

    if( mysqli_num_rows($res) > 0 ){
        while( $row = mysqli_fetch_assoc($res) ) {
            $r[$row['trait_id']] = array('name' => $row['name'], 'icon' => $row['icon']);
        }
        $res->free();
        return $r;
    } else {
        return false;
    }
}

// Gets a list of all the interests for this user
function get_user_interests($db_conn) {
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

// Gets a list of all the traits for this user
function get_user_traits($db_conn) {
    $traits = array();
    $query = "SELECT trait_id FROM traits WHERE user_id = {$_SESSION['user_id']}";
    $res = mysqli_query($db_conn, $query);

    if( mysqli_num_rows($res) > 0 ) {
        while( $row = mysqli_fetch_assoc($res) ) {
            array_push($traits, $row['trait_id']);
        }
        $res->free(); 
        return $traits;
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