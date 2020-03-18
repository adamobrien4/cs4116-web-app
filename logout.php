<?php

include "./includes/session_manager.php";

session_start();

logout();

print "<h2>You are logged out!</h2>";
print "<p><a href='http://hive.csis.ul.ie/cs4116/17226864/'>Home</a></p>";

?>