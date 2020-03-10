<?php

session_start();

function login_check($state) {
    switch($state) {
        case 0:
            // User must be logged out to visit this page
            if( isset($_SESSION['email']) || isset($_SESSION['id']) ){
                die("User must be logged out to visit this page : <a href='http://localhost/cs4116-web-app'>click here</a>");
            }
        break;
        case 1:
            // User must be logged in to visit this page
            if( !isset($_SESSION['email']) || !isset($_SESSION['id']) ){
                die("User must be logged out to visit this page : <a href='http://localhost/cs4116-web-app'>click here</a>");
            }
        break;
    }
}

?>