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

    $query = "SELECT interest_id FROM available_interests WHERE name LIKE '%{$term}%'";

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

    $query = "SELECT trait_id FROM available_traits WHERE name LIKE '%{$term}%'";

    $res = mysqli_query($db_conn, $query);

    if (mysqli_num_rows($res) > 0) {
        while ($row = mysqli_fetch_assoc($res)) {
            array_push($r, $row['trait_id']);
        }
        die(json_encode($r));
    }

    die("not_found");
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


        if(isset($search_data['traits'])){

            $trait_ids = $search_data['traits'];

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

        // Get the data related to the resulting users
        $query = "SELECT users.user_id, firstname, lastname, age, gender, seeking, photo FROM users LEFT JOIN profiles ON profiles.user_id=users.user_id WHERE users.user_id in (";

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
    die('error-data_supply');
}

?>