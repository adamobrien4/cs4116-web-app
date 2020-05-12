<?php

include_once('../vendor/autoload.php');
\Dotenv\Dotenv::createImmutable('../')->load();

include '../includes/db_conn.php';
include '../includes/login_check.php';
include '../includes/helper_functions.php';

// Allow only logged in users to visit this page
login_check(3);


// Retrieve profile data from current user
$user_profile_data = get_profile_data($db_conn, $_SESSION['user_id']);

if ($user_profile_data == null) {
    // User profile not found
    echo "User profile not found";
} else {
    //var_dump($user_profile_data);
}

$status = false;
if (isset($_GET['status'])) {
    $status = true;
}

// Get interest information
$available_interests = get_available_interests($db_conn);
$user_interests = get_user_interests($db_conn);

// Get trait information
$available_traits = get_available_traits($db_conn);
$user_traits = get_user_traits($db_conn);

?>

<!DOCTYPE HTML>

<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Profile</title>
    <link rel="stylesheet" href="..\assets/css/Sidebar-Menu-1.css">
    <link rel="stylesheet" href="..\assets/css/Sidebar-Menu.css">
    <link rel="stylesheet" href="..\assets/css/styles.css">


    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css">

    <link rel="stylesheet" href="profile.css">


    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

    <script type="module" src="../node_modules/sortablejs/Sortable.js"></script>
    <!-- Fontawesome Icons -->
    <script src="https://kit.fontawesome.com/3aa3856778.js" crossorigin="anonymous"></script>
    <script src="./profile.js"></script>

    <script>
        var user_profile_data = <?php echo json_encode($user_profile_data) ?>;

        var available_interests = <?php echo json_encode($available_interests); ?>;
        var user_interests_php = <?php echo json_encode($user_interests); ?>;
        var user_interests = [];
        // Convert interests array from string to Number
        if (user_interests_php.length > 0) {
            user_interests = user_interests_php.map(Number);
        }

        /* user_interests
            index = ranking : 0 is best, 1 is second ... 4 is last
            value = interest_id
        */

        var available_traits = <?php echo json_encode($available_traits) ?>;
        var user_traits_php = <?php echo json_encode($user_traits) ?>;
        var user_traits = [];
        // Convert traits array from string to Number
        if (user_traits_php.length > 0) {
            user_traits = user_traits_php.map(Number);
        }

        <?php if ($status) { ?>;
            show_updated_notification();
        <?php } ?>
    </script>

    <style>
        .profile { /*CSS to highlight when on current page for navbar*/
            background-color:#ec335a;
            color:white !important;
        }

    </style>

</head>

<body>

    <div id="wrapper">
        <?php include_once('../navbar.php'); ?>

        <div class="alert" role="alert" id="complete-status-alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span>&times;</span></button>
            <strong id="alert-content"></strong>
        </div>

        <div class="page-content-wrapper">
            <div class="container-fluid">

                <a class="btn btn-link" role="button" id="menu-toggle" href="#menu-toggle"><i class="fa fa-bars"></i> MENU</a>

                <div class="row">
                    <div class="col-lg-6">
                        <div class="profile-image">
                            <img id="profile-image-preview" src="../assets/uploads/<?php echo $_SESSION['user_id'] ?>.jpg" />
                        </div>

                        <div class="input-group">
                            <div class="input-group-prepend">
                                <button type="button" class="btn btn-outline-success" name="upload-file" id="profile-image-upload-button">Upload file</button>
                            </div>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="profile-image-file" aria-describedby="inputGroupFileAddon01">
                                <label class="custom-file-label" for="inputGroupFile01">Choose file</label>
                            </div>
                        </div>

                        <fieldset>
                            <legend>Interests: </legend>
                            <div id="addInterestsMenu"></div>
                            <ul class="list-group clearfix" id="interests-list"></ul>
                        </fieldset>
                        <button style="margin: 15px 0px" type="button" class="btn sbmt-btn" onclick='submitInterests()'>Submit Interests</button>

                        <fieldset>
                            <legend>Traits: </legend>
                            <div id="addTraitsMenu"></div>
                            <ul class="list-group clearfix" id="traits-list"></ul>
                        </fieldset>
                        <button style="margin: 15px 0px" type="button" class="btn sbmt-btn" onclick='submitTraits()'>Submit Traits</button>
                    </div>

                    <div class="col-lg-6">
                        <h1>Profile / Account Details</h1>
                        <form action="profile_handler.php" method="post">
                            <div class="form-group">
                                <input type="text" class="form-control form-control-sm" name="firstname" id="firstname" placeholder="First Name">
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control form-control-sm" name="lastname" id="lastname" placeholder="Last Name">
                            </div>
                            <div class="form-group">
                                <input type="number" class="form-control form-control-sm" name="age" id="age" min="18" max="75" placeHolder="Age">
                            </div>
                            <fieldset>
                                <legend>I am a: </legend>
                                <label for="gender-m">Male</label>
                                <input type="radio" name="gender" id="gender-m" value="male">
                                <label for="gender-f">Female</label>
                                <input type="radio" name="gender" id="gender-f" value="female">
                            </fieldset>
                            <fieldset>
                                <legend>Seeking a: </legend>
                                <label for="seeking-m">Male</label>
                                <input type="radio" name="seeking" id="seeking-m" value="male">
                                <label for="seeking-f">Female</label>
                                <input type="radio" name="seeking" id="seeking-f" value="female">
                            </fieldset>
                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea name="description" class="form-control" id="description" rows="5"></textarea>
                            </div>

                            <button type="submit" class="btn btn-sm sbmt-btn">Submit Changes</button>
                        </form>
                    </div>
                </div>

                <!-- Footer -->
                <footer style="background: rgb(91,91,110);
background: radial-gradient(circle, rgba(91,91,110,1) 0%, rgba(115,115,125,1) 34%, rgba(154,154,162,1) 58%, rgba(174,174,180,1) 77%, rgba(202,202,206,1) 87%, rgba(218,218,221,1) 93%, rgba(234,234,236,1) 96%, rgba(255,255,255,1) 100%); margin-top:25px;" class="page-footer font-small teal pt-4">

                    <!-- Footer Text -->
                    <div class="container-fluid text-center text-md-left">

                        <!-- Grid row -->
                        <div class="row">

                            <!-- Grid column -->
                            <div class="col-md-6 mt-md-0 mt-3">

                                <!-- Content -->
                                <h5 style="color:#ec335a" class="text-uppercase font-weight-bold">Dating Sucks</h5>
                                <p style="color: #4b2638">Dating Sucks, the world is getting busier, people are getting busier and quite frankly online dating sites suck. Here at Dating Sucks we will make your life
                                easier; when you look for someone our proprietary artificial intelligence enabled matching algorithm is going to find someone you match up with. It doesn't suck, it works.</p>

                            </div>
                            <!-- Grid column -->

                            <hr class="clearfix w-100 d-md-none pb-3">

                            <!-- Grid column -->
                            <div class="col-md-6 mb-md-0 mb-3">

                                <!-- Content -->
                                <h5 style="color:#ec335a"  class="text-uppercase font-weight-bold">About</h5>
                                <p style="color: #4b2638">DatingSucks is developed by a team of top rated developers who are studying in the University of Limerick, Computer Systems course. Learning most of their web development skills in
                                    Module CS4116.
                                Four engineers stumbled upon a revolutionary algorithm to match users after observings sapians attract each other in nature. Using these fundamental principles Dating Sucks was born.</p>
                                <!--<img src="../logo.png" width="100" height="100">-->

                            </div>
                            <!-- Grid column -->

                        </div>
                        <!-- Grid row -->

                    </div>
                    <!-- Footer Text -->

                    <!-- Copyright -->
                    <div style="color:#d9d5d6" class="footer-copyright text-center py-3">© 2020 Copyright:
                        <a style="color:#d9d5d6" href="http://hive.csis.ul.ie/cs4116/group02/index.php"> DatingSucks</a>
                    </div>
                    <!-- Copyright -->

                </footer>
                <!-- Footer -->



            </div> <!--All page content must go inside the container-fluid-->
        </div>
    </div>
    <script src="..\assets/js/Sidebar-Menu.js"></script>
</body>

</html>