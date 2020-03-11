<?php

include "./includes/session_manager.php";

session_start();

logout();

print "<h2>You are logged out!</h2>";
print "<p><a href='http://localhost/cs4116-web-app/'>Home</a></p>";

?>