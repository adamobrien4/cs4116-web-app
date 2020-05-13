<?php

include_once('../vendor/autoload.php');
\Dotenv\Dotenv::createImmutable('../')->load();

include '../includes/db_conn.php';
include '../includes/login_check.php';
include '../includes/admin_helper_functions.php';



// Allow only logged in users to visit this page
// login_check(1);

$users = array();

$query = "SELECT users.email, users.firstname, users.lastname, users.user_id, profiles.description, profiles.banned FROM users INNER JOIN profiles ON users.user_id = profiles.user_id";

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
    <link rel="stylesheet" href="bootstrap.min.css">

    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>

    <script src='admin.js'></script>

    <script>
        var user_list = <?php echo json_encode($users) ?>;
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


            foreach($users as $user) {

                echo "
                <div class='list-group'>
                    <li class='list-group-item'>
                        <div class='user-container'>
                        <a class='user-avatar' href='#'><img class='rounded-circle img-fluid' src='../assets/uploads/{$user['user_id']}.jpg' width='48' height='48' alt='Image' />
                        </a>

                        <label id='user-firstname-{$user['user_id']}' contentEditable='true'>{$user['firstname']}</label>
                        <label id='user-lastname-{$user['user_id']}' contentEditable='true'>{$user['lastname']}</label>

                        <br>

                        <label id='user-email-{$user['user_id']}'>{$user['email']}</label>

                        <br>

                        <textarea id='user-bio-{$user['user_id']}' class='form-control'>{$user['description']}</textarea>
                        
                        <br>
                        
                        <span>Ban User</span>
                        <label class='switch'>
                            <input id='user-banned-{$user['user_id']}' type='checkbox' onchange='toggle_user_ban({$user['user_id']})' ";
                                if($user['banned'] == 1) {
                                    echo "checked";
                                }
                echo ">
                            <span class='slider round'>
                            </span> 
                        </label>
                        
                        <span>Delete User</span>
                        <label class='switch'>
                            <input id='user-deleted-{$user['user_id']}' type='checkbox' onchange='delete_user({$user['user_id']})'>
                            <span class='slider round'>
                            </span> 
                        </label>

                        <br>

                        <button type='button' class='btn btn-primary' onclick='update_user({$user['user_id']})'>Update Profile</button>
                </div>
                </div>
                ";
            }
                        
            ?>
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