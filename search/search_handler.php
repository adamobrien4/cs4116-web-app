<?php

include_once('../vendor/autoload.php');
\Dotenv\Dotenv::createImmutable('../')->load();

include '../includes/db_conn.php';
include '../includes/login_check.php';
include '../includes/helper_functions.php';

// Allow only logged in users to visit this page
login_check(1);

if (isset($_POST['search_interests'])) {
    $r = array();

    $term = $_POST['search_interests'];

    $query = "";

    if($term == "*") {
        $query = "SELECT interest_id FROM available_interests";
    } else {
        $query = "SELECT interest_id FROM available_interests WHERE name LIKE '%{$term}%'";
    }

    $res = mysqli_query($db_conn, $query);

    if (mysqli_num_rows($res) > 0) {
        while ($row = mysqli_fetch_assoc($res)) {
            array_push($r, $row['interest_id']);
        }
        die(json_encode($r));
    }

    die("not_found");
}

if (isset($_POST['search_traits'])) {
    $r = array();

    $term = $_POST['search_traits'];

    $query = "";

    if($term == "*") {
        $query = "SELECT trait_id FROM available_traits";
    } else {
        $query = "SELECT trait_id FROM available_traits WHERE name LIKE '%{$term}%'";
    }

    $res = mysqli_query($db_conn, $query);

    if (mysqli_num_rows($res) > 0) {
        while ($row = mysqli_fetch_assoc($res)) {
            array_push($r, $row['trait_id']);
        }
        die(json_encode($r));
    }

    die("not_found");
}

if(isset($_POST['request_connection'])){
    $uid = preg_replace("/[^0-9]+/", "", $_POST['request_connection']);

    if($uid >= 0) {

        $query = "SELECT connection_id FROM connections WHERE (userA_id = {$_SESSION['user_id']} AND userB_id = {$uid}) OR (userA_id = {$uid} AND userB_id = {$_SESSION['user_id']})";

        $res = mysqli_query($db_conn, $query);

        if($res) {
            if(mysqli_num_rows($res) > 0){
                echo "exists";
                die();
            }
        } else {
            die("error_sf");
        }

        $timestamp = date('Y-m-d h:i:s');

        $query = "INSERT INTO connections (userA_id, userB_id, timestamp, result) VALUES ({$_SESSION['user_id']}, {$uid}, '{$timestamp}', 'pending')";

        $res = mysqli_query($db_conn, $query);

        if($res) {
            if(mysqli_affected_rows($db_conn) > 0){
                // Data inserted
                die("success");
            }
        } else {
            echo $query;
            die("error_i");
        }
    }

    die("error_unk");
}

if (isset($_POST['search_data'])) {
    $search_data = $_POST['search_data'];

    if(isset($search_data['gender']) && isset($search_data['seeking']) && isset($search_data['age-range']) && isset($search_data['distance-range']) ){
        $gender = $search_data['gender'];
        $seeking = $search_data['seeking'];
        $age_range = $search_data['age-range'];
        $distance_range = $search_data['distance-range'];

        // Sort by age - and gender and seeking

        $users = [];

        $query = "SELECT user_id FROM profiles WHERE age >= {$age_range[0]} AND age <= {$age_range[1]}";
        
        // If user selected "both" as an option remove these queries from finding possible matches
        if($gender != "both"){
            $query .= " AND seeking = '{$gender}'";
        }
        if($seeking != "both"){
            $query .= "AND gender = '{$seeking}'";
        }
        
        if ($res = mysqli_query($db_conn, $query)) {
            if (mysqli_num_rows($res) > 0) {
                while ($row = mysqli_fetch_assoc($res)) {
                    $users[$row['user_id']] = 0;
                }
            }
        } 
        
        if(isset($search_data['interests'])) {

            $interest_ids = $search_data['interests'];

            if(count($interest_ids) > 0){

                // Interests
                $query = "SELECT user_id, rank FROM interests WHERE user_id in (";
                foreach($users as $user => $val) {
                    $query .= "{$user},";
                }
                $query = rtrim($query, ",") . ") AND interest_id in (";
                foreach($interest_ids as $iid) {
                    $query .= "{$iid},";
                }
                $query = rtrim($query, ",") . ")";

                if( $res = mysqli_query($db_conn, $query) ){
                    if( mysqli_num_rows($res) > 0 ){
                        while($row = mysqli_fetch_assoc($res)) {
                            if(!isset($users[$row['user_id']])){ $users[$row['user_id']] = 0; }
                            $users[$row['user_id']] += (60 - $row['rank'] * 10) / 2;
                        }
                    } else {
                        // die('not_found');
                    }
                }

            }
        }


        if(isset($search_data['traits'])){

            $trait_ids = $search_data['traits'];

            if(count($trait_ids) > 0){

                // Traits
                $query = "SELECT user_id FROM traits WHERE user_id in (";
                foreach($users as $user => $val) {
                    $query .= "{$user},";
                }
                $query = rtrim($query, ",") . ") AND trait_id in (";
                foreach($trait_ids as $tid) {
                    $query .= "{$tid},";
                }
                $query = rtrim($query, ",") . ")";

                if( $res = mysqli_query($db_conn, $query) ){
                    if( mysqli_num_rows($res) > 0 ){
                        while($row = mysqli_fetch_assoc($res)) {
                            if(!isset($users[$row['user_id']])){ $users[$row['user_id']] = 0; }
                            $users[$row['user_id']] += 20;
                        }
                    } else {
                        // die('not_found');
                    }
                }

            }
        }



        //this is where you should check for other users youve already tried to connect with // ppl declined you , already matched with you or pending
        $already_connected_user_ids = array();
        // Get list of users who the current user is already 'interacted' with
        $sqlT = "SELECT IF(TABLE2.userA_id = {$_SESSION['user_id']}, TABLE2.userB_id, TABLE2.userA_id) AS other_user_id, TABLE2.connection_id FROM ( SELECT connection_id, userA_id, userB_id FROM connections WHERE (userA_id = {$_SESSION['user_id']} OR userB_id = {$_SESSION['user_id']}) ) AS TABLE2";
        $queryT = mysqli_query($db_conn, $sqlT);

        if ($queryT) {
            if (mysqli_num_rows($queryT) > 0) {
                // User has connections linked to their account
                while ($row = mysqli_fetch_assoc($queryT)) {
                    array_push($already_connected_user_ids, $row['other_user_id']);
                }
            }
        }

        //by adding this it means your name shouldnt show up as someone to connect with
        array_push($already_connected_user_ids, $_SESSION['user_id']);


        foreach ($users as $userID => $weight) {

            foreach ($already_connected_user_ids as $key => $matched_userID){
                if ($userID == $matched_userID){
                    unset($users[$userID]);
                }
            }
        }



        if( count($users) > 0){
            // Get the data related to the resulting users
            $query = "SELECT users.user_id, firstname, lastname, age, gender, seeking FROM users LEFT JOIN profiles ON profiles.user_id=users.user_id WHERE users.user_id in (";

            foreach($users as $key => $value){
                $query .= "{$key},";
            }

            $query = rtrim($query, ",") . ")";
            
            if($res = mysqli_query($db_conn, $query)){

                $result = [];
                if(mysqli_num_rows($res) > 0){
                    while($row = mysqli_fetch_assoc($res)){
                        $row['score'] = $users[$row['user_id']];
                        array_push($result, $row);
                    }
                } else {
                    die("not_found");
                }
            } else {
                die("error-s : " . $query);
            }

            echo json_encode($result);
            die();
        }
    } else {
        die('error-data_supply');
    }
}

?>