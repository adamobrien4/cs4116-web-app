<?php

include_once('./vendor/autoload.php');
\Dotenv\Dotenv::createImmutable(__DIR__)->load();

include './includes/db_conn.php';
include "./includes/login_check.php";

login_check(0);

$user_data = array();

if(isset($_GET['g']) && isset($_GET['s']) && isset($_GET['a'])) {
    $gender = preg_replace("/[^a-z]+/", "", $_GET['g']);
    $seeking = preg_replace("/[^a-z]+/", "", $_GET['s']);
    $age = preg_replace("/[^0-9]+/", "", $_GET['a']);

    if($gender != "male" && $gender != "female") {
        header('location: {$_ENV["site_home"]}login.php?n=invalid_gender');
        die();
    }
    if($seeking != "male" && $seeking != "female") {
        header('location: {$_ENV["site_home"]}login.php?n=invalid_seeking');
        die();
    }
    if($age < 18 || $age > 75) {
        header('location: {$_ENV["site_home"]}login.php?n=invalid_age');
        die();
	}
	
	$age_l = $age - 5;
	$age_h = $age + 5;

	$query = "SELECT user_id FROM profiles WHERE gender = '{$seeking}' AND seeking = '{$gender}' AND age > {$age_l} AND age < {$age_h} LIMIT 3";
	$res = mysqli_query($db_conn, $query);

	if($res) {
		if(mysqli_num_rows($res) > 0) {
			$user_ids = array();
			while($row = mysqli_fetch_assoc($res)){
				array_push($user_ids, $row['user_id']);
			}
			var_dump($user_ids);
			$query = "SELECT firstname FROM users WHERE user_id in (" . implode(",", $user_ids) . ")";
			$res = mysqli_query($db_conn, $query);

			if($res) {
				if(mysqli_num_rows($res) > 0) {
					while($row = mysqli_fetch_assoc($res)) {
						array_push($user_data, $row['firstname']);
					}
				}
			} else {
				die("SQL error could not be executed");
			}
		}
	} else {
		die("SQL error could not be executed");
	}
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Login - Simple Browse</title>

	<script src="https://code.jquery.com/jquery-1.12.4.js"></script>

	<link rel="stylesheet" href="webstyle.css">

	<script>
		var user_data = <?php echo json_encode($user_data) ?>;
	</script>
	<script src="./js/register.js"></script>
</head>

<body>
	<div class="container-fluid">
		<div class="main">

			<div id="login" class="contentbox">
				<div class="loginbox">
					<h1 class="headertext">DatingSucks</h1>
					<form class="col-10 mx-auto" action="./handlers/register_handler.php" method="post">
						<div class="pass">
							<input type="name" class="field" name="firstname" placeholder="Firstname">
						</div>
						<div class="form-group">
							<input type="name" class="field" name="lastname" placeholder="Lastname">
						</div>
						<div class="form-group">
							<input type="email" class="field" name="email" placeholder="Email">
						</div>
						<div class="form-group">
							<input type="password" class="field" name="psw1" placeholder="Password">
						</div>
						<div class="form-group">
							<input type="password" class="field" name="psw2" placeholder="Repeat password">
						</div>
						<input type="submit" class="cbtn" value="Register">
					</form>
					<div class="registerinfo">
						<label class="registertext">Already have an account? </label>
						<a href="./login.php">Login</a>
					</div>
				</div>
			</div>

			<div id="formbox" class="contentbox">
				<div class="browsebox">
					<h1 class="headertext">Results</h1>
					<ul id="results-list">

					</ul>
				</div>
			</div>

		</div>
	</div>
</body>

</html>