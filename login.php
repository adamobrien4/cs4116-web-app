<?php

include_once('./vendor/autoload.php');
\Dotenv\Dotenv::createImmutable(__DIR__)->load();

include "./includes/login_check.php";

login_check(0);

$notification_title = null;
$notification_message = null;
$notification_type = "primary";

if(isset($_GET['n'])) {
	switch($_GET['n']) {
		case "register_success":
			$notification_title = "Registration Successful";
			$notification_message = "You have sucessfully registered!";
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
	}
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Login - Simple Browse</title>

	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<link rel="stylesheet" href="webstyle.css">

	<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</head>

<body>

	<?php if($notification_message) { ?>
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

	<div class="container-fluid">
		<div class="main">
			<div id="login" class="contentbox">
				<div class="loginbox">
					<h1 class="headertext">DatingSucks</h1>
					<form class="col-10 mx-auto" action="./handlers/login_handler.php" method="post">
						<div class="pass">
							<input type="email" class="field" name="email" placeholder="Email">
						</div>
						<div class="form-group">
							<input type="password" class="field" name="password" placeholder="Password">
						</div>
						<input type="submit" class="cbtn" value="Login">
					</form>
					<div class="registerinfo">
						<label class="registertext">Don't have an account? </label>
						<a href="./register.php">Sign Up</a>
					</div>
				</div>
			</div>

			<div id="formbox" class="contentbox">
				<div class="browsebox">
					<h1 class="headertext">Browse</h1>
					<form id="form" action="" method="POST">
						<div>
							<select class="optionbox">
								<option style="color: rgb(218, 218, 218)" class="dropdownfade" value="Gender" disabled selected>LOAD FROM DATABASE?</option>
								<option value="male"> x </option>
								<option value="female"> y </option>
							</select>
						</div>

						<div>
							<select class="optionbox">
								<option style="color: rgb(218, 218, 218)" class="dropdownfade" value="Gender" disabled selected>LOAD FROM DATABASE?</option>
								<option value="male"> x </option>
								<option value="female"> y </option>
							</select>
						</div>

						<div>
							<select class="optionbox">
								<option style="color: rgb(218, 218, 218)" class="dropdownfade" value="Gender" disabled selected>LOAD FROM DATABASE?</option>
								<option value="male"> x </option>
								<option value="female"> y </option>
							</select>
						</div>

						<div>
							<input style=" color: #3498db" class="field" type="text" placeholder="INT AGE" id="age" name="age" value=""><br>
						</div>

						<div>
							<button type="submit" class="cbtn" name="save">Find Users(no logic)</button>
						</div>
					</form>
				</div>
			</div>

		</div>
	</div>
</body>

<script>
	$(() => {
		$('#myModal').modal("show");
	});
</script>

</html>