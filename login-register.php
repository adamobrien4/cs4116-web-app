<?php

include "./includes/login_check.php";

login_check(0);

?>

<!doctype html>

<head>

</head>

<body>
    <div>
        <h1>Login</h1>
        <form action="login_process.php" method="post">
        <label for="email">Email</label>
            <input type="text" name="email" id="email" placeholder="Email">

            <label for="password">Password</label>
            <input type="password" name="password" id="password" placeholder="Password">
            <button type="submit">Login</button>
        </form>

        <hr>

        <h1>New here? - Sign Up</h1>
        <form action="register_process.php" method="post">
            <label for="email">Email</label>
            <input type="text" name="email" id="email" placeholder="Email">

            <label for="psw1">Password</label>
            <input type="password" name="psw1" id="psw1" placeholder="Password">

            <label for="psw2">Repeat Password</label>
            <input type="password" name="psw2" id="psw2" placeholder="Repeat Password">

            <button type="submit">Register</button>
        </form>
    </div>
</body>

</html>