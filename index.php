<?php

session_start();

?>

<!doctype html>

<head>

</head>

<body>
    <ul>
        <li><a href="http://localhost/cs4116-web-app/login-register.php">Login / Register</a></li>
        <li><a href="http://localhost/cs4116-web-app/profile.php">Profile Page</a></li>
        <?php if(isset($_SESSION['email'])){ print "<li><a href='http://localhost/cs4116-web-app/logout.php'>Logout</a></li>"; } ?>
    </ul>
</body>

</html>