<?php

session_start();

?>


<!doctype html>

<head>
    <title>Profile Page</title>
</head>

<body>
    <h1>Profile Page</h1>
    <?php if(isset($_SESSION['email'])){
        print "<h2>Welcome, " . $_SESSION['email'] . "!</h2>";
    } else {
        print "<h2>You are curently not logged in.</h2><br><p>Click <a href='hive.csis.ul.ie/cs4116/17226864'>here</a> to log in.'</p>";
    }
    ?>
</body>

</html>