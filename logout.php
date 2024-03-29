<?php

include_once('./vendor/autoload.php');
\Dotenv\Dotenv::createImmutable(__DIR__)->load();

session_start();

session_destroy();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body onload="redirect()">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1>You have been sucessfully logged out.</h1>
                <span>Please wait until you are redirected.</span>
            </div>
        </div>
    </div>

    <script>

    function redirect() {
        setTimeout(()=>{
            window.location = "<?php echo $_ENV['site_home']; ?>login.php";
        }, 1500);
    }

    </script>
</body>
</html>