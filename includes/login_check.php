<?php

session_start();

function login_check($state) {
    switch($state) {
        case 0:
            // User must be logged out to visit this page
            if( isset($_SESSION['email']) || isset($_SESSION['user_id']) ){

                // idea is to have a basic styled html page here - give user option to log out or redirect back to home after 7 seconds
                //echo "<p>You can log out or be redirected back to the home page</p>";
                echo "<div class=\"card\" style=\"width: 18rem;\">
                            <div class=\"card-body\">
                                <h5 class=\"card-title\">Logged In</h5>
                                <h6 class=\"card-subtitle mb-2 text-muted\">Card subtitle</h6>
                                <p class=\"card-text\">If you enter the login page while you are already logged in // does having output html on this page mean that you are brought here more than if you didny have html?</p>
                                <a href=\"#\" class=\"card-link\">Card link</a>
                                <a href=\"#\" class=\"card-link\">Another link</a>
                            </div>
                      </div>";
                die("User must be logged out to visit this page : <a href='{$_ENV['site_home']}logout.php'>click here</a>");
            }
        break;
        case 1:
            // User must be logged in to visit this page
            if( !isset($_SESSION['email']) || !isset($_SESSION['user_id']) ){
                die("User must be logged in to visit this page : <a href='{$_ENV['site_home']}login.php'>click here</a>");
            }
        break;
    }
}

?>
