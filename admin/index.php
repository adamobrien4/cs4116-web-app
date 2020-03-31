<?php

include_once('../vendor/autoload.php');
\Dotenv\Dotenv::createImmutable('../')->load();

include '../includes/db_conn.php';
include '../includes/login_check.php';
include '../includes/helper_functions.php';

// Allow only logged in users to visit this page
login_check(1);


?>
<!DOCTYPE html>
<html lang = "en">
<head>
    <title>Admin Portal</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <link rel="stylesheet" href="..\assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="..\assets/fonts/font-awesome.min.css">
    <link rel="stylesheet" href="..\assets/css/Sidebar-Menu-1.css">
    <link rel="stylesheet" href="..\assets/css/Sidebar-Menu.css">
    <link rel="stylesheet" href="..\assets/css/styles.css">



</head>


<body>

<?php include ('..\navbar.php'); ?>

<div class="page-content-wrapper">
    <div class="container-fluid"><a class="btn btn-link" role="button" id="menu-toggle" href="#menu-toggle"><i class="fa fa-bars"></i> MENU</a>

        <h1>Admin Portal</h1>

        <h4>Create a list of cards of all users -- refer to adobe xd design doc -- admin portal last of the main features to be implemented</h4>
        <h4>Maybe only have display 100 (x) at a time - might be easier on the sql queries</h4>
        <h4>Maybe have a basic search functionality</h4>


        </div>

    </div>

    <script src="..\assets/js/jquery.min.js"></script>
    <script src="..\assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="..\assets/js/Sidebar-Menu.js"></script>
</div>





</body>
</html>