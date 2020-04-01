<?php

include_once('./vendor/autoload.php');
\Dotenv\Dotenv::createImmutable(__DIR__)->load();

include './includes/db_conn.php';
include './includes/login_check.php';

// Allow only logged in users to visit this page
login_check(1);

$q = "SELECT * FROM connections WHERE userA_id = {$_SESSION['user_id']} OR userB_id = {$_SESSION['user_id']}";

$res = mysqli_query($db_conn, $q);

if ($res) {
    if (mysqli_num_rows($res) > 0) {
        while ($r = mysqli_fetch_assoc($res)) {
            echo "<pre><code class='prettyprint'>" . json_encode($r) . "</code></pre><br>";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <style>
        @import "https://cdn.rawgit.com/google/code-prettify/master/styles/desert.css";

        body {
            width: 50%;
            margin: 0 auto;
        }

        @media (max-width: 650px) {
            body {
                width: 75%;
            }
        }

        @media (max-width: 430px) {
            body {
                width: 100%;
            }
        }

        code,
        .code,
        pre {
            font-family: 'Source Code Pro';
            background: #292929;
            color: #fafafa;
            font-size: 16px;
            padding: 0;
            padding: 10px;
        }

        code:before,
        .code:before,
        pre:before {
            display: block;
            width: calc(100%);
            margin-left: -3px;
            margin-top: -3px;
            padding: 3px;
            text-transform: uppercase;
            content: attr(data-lang);
            background: #9baecf;
        }

        code .o,
        .code .o,
        pre .o {
            color: orange;
        }

        code .w,
        .code .w,
        pre .w {
            color: white;
        }
    </style>
</head>

<body>

</body>

</html>