<?php

include_once('../vendor/autoload.php');
\Dotenv\Dotenv::createImmutable('../')->load();

include '../includes/db_conn.php';
include '../includes/login_check.php';
include '../includes/admin_helper_functions.php';



// Allow only logged in users to visit this page
//login_check(1);

$users = array();

$query = "SELECT users.firstname, users.lastname, users.user_id, profiles.description FROM users INNER JOIN profiles ON users.user_id = profiles.user_id";

$res = mysqli_query($db_conn, $query);

if ($res) {
    if (mysqli_num_rows($res) > 0) {
        while ($row = mysqli_fetch_assoc($res)) {
            array_push($users, $row);
        }
    }
}


?>




<!DOCTYPE html>
<html lang="en">

<head>
    <title>Admin Portal</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">


    <link rel="stylesheet" href="..\assets/css/Sidebar-Menu-1.css">
    <link rel="stylesheet" href="..\assets/css/Sidebar-Menu.css">
    <link rel="stylesheet" href="..\assets/css/styles.css">
    <link rel="stylesheet" href="admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css">


    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>

    <script src='admin.js'></script>

    <script>
        var user_list = <?php echo json_encode($user_list) ?>;
    </script>

</head>


<body>

    <div id="wrapper">
        <?php include('..\navbar.php'); ?>

        <div class="page-content-wrapper">
            <div class="container-fluid">
                <a class="btn btn-link" role="button" id="menu-toggle" href="#menu-toggle"><i class="fa fa-bars"></i> MENU</a>

                <!-- <h1>Admin Portal</h1>

        <h4>Create a list of cards of all users -- refer to adobe xd design doc -- admin portal last of the main features to be implemented</h4>
        <h4>Maybe only have display 100 (x) at a time - might be easier on the sql queries</h4>
        <h4>Maybe have a basic search functionality</h4>
 -->
            <?php
           /*  $dir = "../assets/uploads/";
            open directory and read what's inside
            testing
             if (is_dir($dir)){
              if ($dh = opendir($dir)){
                while (($file = readdir($dh)) !== false){
                  echo "filename:" . $file . "<br>";
                }
                closedir($dh);
              }
            } 
             */

            foreach($users as $user) {
                echo "
                <div class='container'>
                    <div class='row user-list'>
                        <div class='col-12 col-sm-6 col-md-4 col-lg-3 user-item'>
                        <div class='user-container'><a class='user-avatar' href='#'><img class='rounded-circle img-fluid' src='../assets/uploads/{$user['user_id']}.jpg' width='48' height='48' alt='Image' /></a>
                        <p class='user-name' id='name' contentEditable='true'>{$user['firstname']}, {$user['lastname']}
                        <ul id='bio'>{$user['description']} </ul>
                            </p>
                            <a class='user-delete' href='#'><i class='fa fa-remove'></i></a>
                        </div>
                    </div>
                </div>
                </div>
                ";
            } ?>
                </div>
            </div>
            <script src="..\assets/js/Sidebar-Menu.js"></script>
        </div>
    </div>

</body>

</html>


<!-- <a class="user-delete" href="#">
                        <i class="fa fa-remove"></i>
                    </a> -->