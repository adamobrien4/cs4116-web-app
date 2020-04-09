<?php

session_start();

include_once('./vendor/autoload.php');
\Dotenv\Dotenv::createImmutable(__DIR__)->load();

include('./includes/db_conn.php');

var_dump($_SESSION);

$interests = array();
$traits = array();

$matching_users = array();
$matching_user_ids = array();

// Get users interests
$interests_q = "SELECT interest_id FROM interests WHERE user_id = {$_SESSION['user_id']}";
$res = mysqli_query($db_conn, $interests_q);

if($res){
    if(mysqli_num_rows($res) > 0) {
        while($row = mysqli_fetch_assoc($res)){
            array_push($interests, $row['interest_id']);
        }
    }
}

// Get users traits
$interests_t = "SELECT trait_id FROM traits WHERE user_id = {$_SESSION['user_id']}";
$res = mysqli_query($db_conn, $interests_t);

if($res){
    if(mysqli_num_rows($res) > 0) {
        while($row = mysqli_fetch_assoc($res)){
            array_push($traits, $row['trait_id']);
        }
    }
}

echo "<h1>Interests</h1>";
var_dump($interests);
echo "<h1>Traits</h1>";
var_dump($traits);

$user_details = array();

// Get current users details
$sql = "SELECT age, gender, seeking FROM profiles WHERE user_id = {$_SESSION['user_id']}";
$r = mysqli_query($db_conn, $sql);

if($r){
    if(mysqli_num_rows($r) > 0) {
        $user_details = mysqli_fetch_assoc($r);
    }
}

echo "<h4>User Details</h4>";
var_dump($user_details);

$age_range_low = $user_details['age'] - 20;
$age_range_high = $user_details['age'] + 20;

$sql = "SELECT user_id FROM profiles WHERE gender = '{$user_details['seeking']}' AND seeking = '{$user_details['gender']}' AND age BETWEEN {$age_range_low} AND {$age_range_high}";
echo($sql);
$r = mysqli_query($db_conn, $sql);

if($r){
    if(mysqli_num_rows($r) > 0){
        while($row = mysqli_fetch_assoc($r)){
            array_push($matching_user_ids, $row['user_id']);
        }
    }
}

echo("<h2>Matching Users</h2>");
echo(json_encode($matching_user_ids));


// Find users who have matching interests
$sql = "SELECT user_id FROM interests WHERE interest_id IN (" . implode(",", $interests) . ")";
$res = mysqli_query($db_conn, $sql);

if($res){
    if(mysqli_num_rows($res) > 0) {
        while($row = mysqli_fetch_assoc($res)){
            if(isset($matching_users[$row['user_id']])){
                $matching_users[$row['user_id']] += 20;
            } else {
                $matching_users[$row['user_id']] = 20;
            }
        }
    }
}

// Find users who have matching traits
$sql = "SELECT user_id FROM traits WHERE trait_id IN (" . implode(",", $traits) . ")";
$res = mysqli_query($db_conn, $sql);

if($res){
    if(mysqli_num_rows($res) > 0) {
        while($row = mysqli_fetch_assoc($res)){
            if(isset($matching_users[$row['user_id']])){
                $matching_users[$row['user_id']] += 20;
            } else {
                $matching_users[$row['user_id']] = 20;
            }
        }
    }
}

// Get other matching information

echo("<h2>Matching Users</h2>");
echo(json_encode($matching_users));

$matching_user_ids = array();

// Sort users by score
arsort($matching_users);

// Remove users with low matching scores, not many interests/traits similiar
foreach($matching_users as $index => $user){
    if($user < 25){
        unset($matching_users[$index]);
    } else {
        array_push($matching_user_ids, $index);
    }
}

echo "<h1>Sorted Users</h1>";
var_dump($matching_users);

?>

<ol>
<?php
foreach($matching_users as $index=>$value){
    echo "<li><span>{$index} -> {$value}</span></li>";
}
?>
</ol>