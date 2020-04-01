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
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Home page</title>

    <link rel="stylesheet" href="styles.css">


    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <link rel="stylesheet" href="..\assets/css/Sidebar-Menu-1.css">
    <link rel="stylesheet" href="..\assets/css/Sidebar-Menu.css">
    <link rel="stylesheet" href="..\assets/css/styles.css">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css">
    <link rel="stylesheet" href="search.css">

    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <!-- Fontawesome Icons -->
    <script src="https://kit.fontawesome.com/3aa3856778.js" crossorigin="anonymous"></script>


</head>

<body>

<div id="wrapper">
    <?php include('..\navbar.php'); ?>
    <div class="page-content-wrapper">
        <div class="container-fluid">
            <a class="btn btn-link" role="button" id="menu-toggle" href="#menu-toggle"><i class="fa fa-bars"></i> MENU</a>

<div class="card text-center">
    <div class="card-body"><img />
        <h4 class="card-title">Maggy, 34</h4>
        <h6 class="text-muted card-subtitle mb-2">Limerick</h6>
        <hr />
        <h6 class="text-muted card-subtitle mb-2">DatingSuck's proprietary algorithm says: 87% chance ye two match up well hai</h6>

        <!-- Might look nice to have a circular progress bar-->
        <div class="project-progress">
            <div class="progress">
                <div role="progressbar" style="width: 87%; height: 16px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" class="progress-bar bg-red">
                </div>
            </div>
        </div>

        <!--Might be funny to have a funny quotes from the dating sucks team on things not to say on a first date

        if you match with John dont bring up your third ex wife etc.. etc..
          -->


        <div class="container bio">
            <p>This is a big bio about me, my name is MM MM and I love dating however dating sucks for a woman like me and so I have enlisted the services of DatingSucks.</p>
        </div>


        <!--Highlight the interests and traits that are in common-->

        <div class="container">
            <div class="bg-c-green counter-block">
                <div class="row">
                    <div class="col-sm">
                        <i class="fa fa-comment"></i>
                        <p>Coding</p>
                    </div>
                    <div class="col-sm">
                        <i class="fa fa-user"></i>
                        <p>Soccer</p>
                    </div>
                    <div class="col-sm">
                        <i class="fa fa-suitcase"></i>
                        <p>Roleplay</p>
                    </div>

                    <div class="col-sm">
                        <i class="fa fa-suitcase"></i>
                        <p>Fantasy</p>
                    </div>

                    <div class="col-sm">
                        <i class="fa fa-suitcase"></i>
                        <p>Suitcase</p>
                    </div>

                </div>
            </div>
        </div>

        <div class="container">
            <div class="bg-c-yellow counter-block">
                <div class="row">
                    <div class="col-sm">
                        <i class="fa fa-comment"></i>
                        <p>Coding</p>
                    </div>
                    <div class="col-sm">
                        <i class="fa fa-user"></i>
                        <p>Soccer</p>
                    </div>
                    <div class="col-sm">
                        <i class="fa fa-suitcase"></i>
                        <p>Roleplay</p>
                    </div>

                    <div class="col-sm">
                        <i class="fa fa-suitcase"></i>
                        <p>Fantasy</p>
                    </div>

                    <div class="col-sm">
                        <i class="fa fa-suitcase"></i>
                        <p>Suitcase</p>
                    </div>

                </div>
            </div>

        </div>

        <!--Maybe this should be a form to accept/reject-->
        <a class="card-link" href="#"><i class="fas fa-heart fa-2x"></i></a><a class="card-link" href="#"><i class="fas fa-times-circle fa-2x"></i></a></div>
</div>

</div>
</div>
</div>
<script src="../assets/js/Sidebar-Menu.js"></script>


</body>

</html>