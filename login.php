<?php

include_once('./vendor/autoload.php');
\Dotenv\Dotenv::createImmutable(__DIR__)->load();

include "./includes/login_check.php";

login_check(0);

$notification_title = null;
$notification_message = null;
$notification_type = "primary";

if (isset($_GET['n'])) {
	switch ($_GET['n']) {
		case "register_success":
			$notification_title = "Registration Successful";
			$notification_message = "You have sucessfully registered!";
			$notification_type = "success";
			break;
		case "creds_incorrect":
			$notification_title = "Incorrect Details";
			$notification_message = "The details you entered did not match an account.<br>Please try again, or register for a new account.";
			$notification_type = "warning";
			break;
		case "login_error":
			$notification_title = "Try Again";
			$notification_message = "An error occurred, please try again.";
			$notification_type = "warning";
			break;
		case "invalid_gender":
			$notification_message = "Invalid gender. Please try again";
			$notification_title = "Form Error";
			$notification_type = "danger";
			break;
		case "invalid_seeking":
			$notification_message = "Invalid seeking option. Please try again";
			$notification_title = "Form Error";
			$notification_type = "danger";
			break;
		case "invalid_age":
			$notification_message = "Invalid age (18-75 only). Please try again";
			$notification_title = "Form Error";
			$notification_type = "danger";
			break;
		case "logout_suc":
			$notification_message = "You have been sucessfully logged out.";
			$notification_title = "Logout Success";
			$notification_type = "success";
			break;
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
	<title>Login</title>

	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<link rel="stylesheet" href="webstyle.css">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>


	<style>
		#goDownLink:hover {
			text-decoration: none;
		}
	</style>
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

	<div class="main">
		<div class="image-bg">
			<div style="object-fit: contain; bottom:0">
				<img src="https://i.imgur.com/EqeAKFz.png" alt="" width="100%" style="filter: opacity(0.2);">
			</div>

		</div>
		<div id="login" class="contentbox">
			<div class="loginbox center_x">
				<div class="center_y" style="width:100%">
					<h1 class="headertext">DatingSucks</h1>
					<form class="c" action="./handlers/login_handler.php" method="post">
						<div class="form-group">
							<input type="email" class="field" name="email" placeholder="Email" required minlength="5">
						</div>
						<div class="form-group">
							<input type="password" class="field" name="password" placeholder="Password" required minlength="6">
						</div>
						<input type="submit" class="cbtn" value="Login">
					</form>
					<div class="registerinfo">
						<label class="registertext">Don't have an account? </label>
						<a href="./register.php">Sign Up</a>
						<a href="#browsebox" id="goDownLink">
							<div class="center_y" style="width:100%;margin-top:80px;" id="goDown">
								<small>Browse Users</small>
								<i class="fa fa-angle-down" style="text-align:center; font-size:30px; font-weight:bold; "></i>
							</div>
						</a>
					</div>
				</div>
			</div>



			<div id="formbox" class="contentbox">
				<div class="loginbox center_x">
					<div class="center_y fullpage">
						<div class="browsebox" id="browsebox">
							<h1 class="headertext">Browse</h1>
							<form id="form" action="./handlers/register_handler.php" method="POST">
								<div>
									<label for="gender">I am a:</label>
									<select class="optionbox" name="gender">
										<option value="male">Male</option>
										<option value="female">Female</option>
									</select>
								</div>

								<div>
									<label for="seeking">Seeking a:</label>
									<select class="optionbox" name="seeking">
										<option value="male">Male</option>
										<option value="female">Female</option>
									</select>
								</div>

								<div>
									<input class="optionbox" type="text" placeholder="Age" id="age" name="age"><br>
								</div>

								<div>
									<button type="submit" class="cbtn" name="save" id="search123" onclick="scrollie()">Find Users</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div>
</body>

<script>
	$(() => {
		$('#myModal').modal("show");
	});

	function scrollie()
	{
		fiveseconds = 1000*5
		var expires = new Date((new Date()).valueOf() + fiveseconds);
		document.cookie = "true; expires=fiveseconds";
	}
	console.log(document.cookie);

	
</script>


</html>