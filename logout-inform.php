<?php

include_once('./vendor/autoload.php');
\Dotenv\Dotenv::createImmutable(__DIR__)->load();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout?</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>

<body>
    <div class="container text-center">
    <img src="https://i.imgflip.com/1nto1n.gif"/>
        <div class="row">
            <div class="col-12">
                <h1></h1>
                <span>The page you tried to access requires you to be logged out.<br><strong>Would you like to logout?</strong></span>

                <br><br>

                <button class="btn btn-primary" onclick="window.location='<?php echo $_ENV['site_home']; ?>logout.php'">Logout</button>
            </div>
        </div>
    </div>
</body>

</html>