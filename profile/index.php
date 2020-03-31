<?php

include_once('../vendor/autoload.php');
\Dotenv\Dotenv::createImmutable('../')->load();

include '../includes/db_conn.php';
include '../includes/login_check.php';
include '../includes/helper_functions.php';

// Allow only logged in users to visit this page
login_check(1);


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

</head>

<body>

    <div id="wrapper">
        <?php include('..\navbar.php'); ?>

        <div class="page-content-wrapper">
            <div class="container-fluid">
                <a class="btn btn-link" role="button" id="menu-toggle" href="#menu-toggle"><i class="fa fa-bars"></i> MENU</a>

                <div class="row">
                    <div class="col-lg-6">
                        <div style="width: 100px; height: 100px; border: solid 2px green;">
                        </div>
                        <form action="index.php" method="post">
                            <input type="file" class="custom-file-input">
                            <label class="custom-file-label" for="customFile">Choose Profile Picture</label>
                            <button type="submit">Update Profile Picture</button>
                        </form>

                        <h1>Interests</h1>
                        <h2>Checkbox</h2>
                        <fieldset>
                            <legend>Interests: </legend>
                            <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#addInterestsMenu" aria-expanded="false" aria-controls="addInterestsMenu">
                                Add an interest
                            </button>
                            <div id="addInterestsMenu" class="collapse"></div>
                            <ul class="list-group clearfix" id="interests-list"></ul>
                        </fieldset>
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick='submitInterests()'>Submit Interests</button>

                        <h1>Traits</h1>
                        <h2>Checkbox</h2>
                        <fieldset>
                            <legend>Traits: </legend>
                            <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#addTraitsMenu" aria-expanded="false" aria-controls="addTraitsMenu">
                                Add a Trait
                            </button>
                            <div id="addTraitsMenu" class="collapse"></div>
                            <ul class="list-group clearfix" id="traits-list"></ul>
                        </fieldset>
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick='submitTraits()'>Submit Traits</button>
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
                                <input type="number" class="form-control form-control-sm" name="age" id="age" placeHolder="Age">
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

                            <button type="submit" class="btn btn-sm btn-outline-primary">Submit Changes</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="..\assets/js/Sidebar-Menu.js"></script>
</body>

</html>