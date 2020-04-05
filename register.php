<?php

include_once('./vendor/autoload.php');
\Dotenv\Dotenv::createImmutable(__DIR__)->load();

//var_dump($_ENV);

include './includes/db_conn.php';
include "./includes/login_check.php";

login_check(0);

$user_data = array();

if (isset($_GET['g']) && isset($_GET['s']) && isset($_GET['a'])) {
	$gender = preg_replace("/[^a-z]+/", "", $_GET['g']);
	$seeking = preg_replace("/[^a-z]+/", "", $_GET['s']);
	$age = preg_replace("/[^0-9]+/", "", $_GET['a']);

	if ($gender != "male" && $gender != "female") {
		header('location: {$_ENV["site_home"]}login.php?n=invalid_gender');
		die();
	}
	if ($seeking != "male" && $seeking != "female") {
		header('location: {$_ENV["site_home"]}login.php?n=invalid_seeking');
		die();
	}
	if ($age < 18 || $age > 75) {
		header('location: {$_ENV["site_home"]}login.php?n=invalid_age');
		die();
	}

	$age_l = $age - 5;
	$age_h = $age + 5;

	$query = "SELECT user_id FROM profiles WHERE gender = '{$seeking}' AND seeking = '{$gender}' AND age > {$age_l} AND age < {$age_h} LIMIT 3";
	$res = mysqli_query($db_conn, $query);

	if ($res) {
		if (mysqli_num_rows($res) > 0) {
			$user_ids = array();
			while ($row = mysqli_fetch_assoc($res)) {
				array_push($user_ids, $row['user_id']);
			}
			$query = "SELECT firstname FROM users WHERE user_id in (" . implode(",", $user_ids) . ")";
			$res = mysqli_query($db_conn, $query);

			if ($res) {
				if (mysqli_num_rows($res) > 0) {
					while ($row = mysqli_fetch_assoc($res)) {
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

$notification_title = null;
$notification_message = null;
$notification_type = "primary";

if (isset($_GET['n'])) {
	switch ($_GET['n']) {
		case "data_not_supplied":
			$notification_message = "Please ensure all fields have been filled out and try again.";
			$notification_title = "Data not supplied";
			$notification_type = "warning";
		break;
		case "register_error":
			$notification_message = "An error occurred, please reload the page and try again.";
			$notification_title = "Registration Error";
			$notification_type = "danger";
		break;
		case "password_mismatch":
			$notification_message = "Please ensure your passwords match and try again.";
			$notification_title = "Password Mismatch";
			$notification_type = "warning";
		break;
	}
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Register your Account</title>

	<script src="https://code.jquery.com/jquery-1.12.4.js"></script>

	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<link rel="stylesheet" href="webstyle.css">

	<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

	<script>
		var user_data = <?php echo json_encode($user_data) ?>;
	</script>
	<script src="./js/register.js"></script>
</head>

<body>

	<?php if ($notification_message) { ?>
		<div id="myModal" class="modal" tabindex="-1" role="dialog">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title"><?php echo $notification_title; ?></h5>
					</div>
					<div class="modal-body">
						<p><?php echo $notification_message; ?></p>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-<?php echo $notification_type; ?>" onclick="$('#myModal').modal('hide')">Okay</button>
					</div>
				</div>
			</div>
		</div>
	<?php } ?>

	<div class="content">
		<div class="main">

			<div id="login" class="contentbox">
				<div class="loginbox">
					<h1 class="headertext">DatingSucks</h1>
					<form class="ok" action="./handlers/register_handler.php" method="post">
						<div class="pass">
							<input type="name" class="field" name="firstname" placeholder="Firstname" required minlength="1">
						</div>
						<div class="form-group">
							<input type="name" class="field" name="lastname" placeholder="Lastname" required minlength="1">
						</div>
						<div class="form-group">
							<input type="email" class="field" name="email" placeholder="Email" required minlength="5">
						</div>
						<div class="form-group">
							<input type="password" class="field" name="psw1" placeholder="Password" required minlength="6">
						</div>
						<div class="form-group">
							<input type="password" class="field" name="psw2" placeholder="Repeat password" required minlength="6">
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
					<div id="results-list">

					</div>
					<div class="card">
						<div class="card-body">
							<h5 class="card-title">Please register to view these profiles</h5>
						</div>
					</div>
				</div>
			</div>

		</div>
	</div>

	<script>
		$(() => {
			$('#myModal').modal("show");
		});
	</script>
</body>

</html>