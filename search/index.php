<?php

include_once('../vendor/autoload.php');
\Dotenv\Dotenv::createImmutable('../')->load();

include '../includes/db_conn.php';
include '../includes/login_check.php';
include '../includes/helper_functions.php';

// Allow only logged in users to visit this page
login_check(1);

// Get trait and interests information
$available_interests = get_available_interests($db_conn);
$available_traits = get_available_traits($db_conn);

$gender = "male";
$seeking = "female";

// Get user details
$sql = "SELECT gender, seeking FROM profiles WHERE user_id = {$_SESSION['user_id']}";

$res = mysqli_query($db_conn, $sql);
if($res) {
    if(mysqli_num_rows($res) > 0){
        $row = mysqli_fetch_assoc($res);

        var_dump($row);

        $gender = $row['gender'];
        $seeking = $row['seeking'];
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Search</title>
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

    <script src="./search.js"></script>

    <script>
        var available_interests = <?php echo json_encode($available_interests) ?>;
        var available_traits = <?php echo json_encode($available_traits) ?>;

        var selected_interests = [];
        var selected_traits = [];

        var age_range = [25, 30];
        var distance_range = [5, 10];

        var gender = '<?php echo $gender ?>';
        var seeking = '<?php echo $seeking ?>';
    </script>

    <title>Document</title>
</head>

<body>
    <div id="wrapper">
        <?php include('..\navbar.php'); ?>
        <div class="page-content-wrapper">
            <div class="container-fluid">
                <a class="btn btn-link" role="button" id="menu-toggle" href="#menu-toggle"><i class="fa fa-bars"></i> MENU</a>
                <div class="row">
                    <div class="col-4">
                        <h2>Filter Options</h2>

                        <div>
                            <label for="filter-age">Age Range:</label>
                            <input type="text" id="filter-age" readonly style="border:0; color:#f6931f; font-weight:bold;">
                            <div id="age-slider-range"></div>
                        </div>

                        <div class="form-group">
                            <label for="filter-distance">Distance Range:</label>
                            <input type="text" id="filter-distance" readonly style="border:0; color:#f6931f; font-weight:bold;">
                            <div id="distance-slider-range"></div>
                        </div>

                        <div class="form-group">
                            <h2>I am a:</h2>
                            <fieldset>
                                <label for="gender-m">Male</label>
                                <input type="radio" name="gender" id="gender-m" value="male" checked>
                                <label for="gender-f">Female</label>
                                <input type="radio" name="gender" id="gender-f" value="female">
                                <label for="gender-b">Both</label>
                                <input type="radio" name="gender" id="gender-b" value="both">
                            </fieldset>
                        </div>

                        <div class="form-group">
                            <h2>Seeking a:</h2>
                            <fieldset>
                                <label for="seeking-m">Male</label>
                                <input type="radio" name="seeking" id="seeking-m" value="male">
                                <label for="seeking-f">Female</label>
                                <input type="radio" name="seeking" id="seeking-f" value="female" checked>
                                <label for="seeking-b">Both</label>
                                <input type="radio" name="seeking" id="seeking-b" value="both">
                            </fieldset>
                        </div>

                        <div class="form-group">
                            <h1>Interests</h1>
                            <fieldset>
                                <legend>Interests: </legend>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="interests-search" placeholder="Search Interests">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-primary" id="interests-search-button">Search Interests</button>
                                    </div>
                                </div>
                                <div id="interests-grid"></div>
                                <ul class="list-group clearfix" id="selected-interests-list"></ul>
                            </fieldset>

                            <h1>Traits</h1>
                            <fieldset>
                                <legend>Traits: </legend>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="traits-search" placeholder="Search Traits">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-primary" id="traits-search-button">Search Traits</button>
                                    </div>
                                </div>
                                <div id="traits-grid"></div>
                                <ul class="list-group clearfix" id="selected-traits-list"></ul>
                            </fieldset>
                        </div>

                        <input type="button" class="btn btn-xl btn-primary" value="Search Users" id="submit-search-button">

                    </div>
                    <div class="col-6">
                        <ul class="list-group" id="search-results-list">

                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="../assets/js/Sidebar-Menu.js"></script>

</body>

</html>