<?php

session_start();

function login_check($state) {
    switch($state) {
        case 0:
            // User must be logged out to visit this page
            if( isset($_SESSION['email']) || isset($_SESSION['id']) ){
                die("User must be logged out to visit this page : <a href='http://hive.csis.ul.ie/cs4116/17226864/logout.php'>click here</a>");
            }
        break;
        case 1:
            // User must be logged in to visit this page
            if( !isset($_SESSION['email']) || !isset($_SESSION['id']) ){
                die("User must be logged in to visit this page : <a href='http://hive.csis.ul.ie/cs4116/17226864/login.php'>click here</a>");
            }
        break;
    }
}

?>