<?php

include "./includes/global_vars.php";

session_start();

?>

<!doctype html>

<head>

</head>

<body>
    <ul>
        <li><a href="<?php echo $website_home; ?>login-register.php">Login / Register</a></li>
        <li><a href="<?php echo $website_home; ?>profile.php">Profile Page</a></li>
        <?php if(isset($_SESSION['email'])){ print "<li><a href='{?php echo $website_home; ?>logout.php'>Logout</a></li>"; } ?>
    </ul>
</body>

</html>