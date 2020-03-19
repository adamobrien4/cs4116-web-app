<?php

include_once('../vendor/autoload.php');
\Dotenv\Dotenv::createImmutable('../')->load();

include '../includes/db_conn.php';
include '../includes/login_check.php';
include '../includes/helper_functions.php';

// Allow only logged in users to visit this page
login_check(1);


// Retrieve profile data from current user
$user_profile_data = get_profile_data($db_conn, $_SESSION['user_id']);

if( $user_profile_data == null) {
	// User profile not found
	echo "User profile not found";
} else {
	var_dump($user_profile_data);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Profile</title>

	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

	<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

	<script>
		var user_profile_data = <?php echo json_encode($user_profile_data) ?>;
	</script>

	<script src="./profile.js"></script>
</head>
<body>
	<div class="container-fluid">
		<div class="row">
			<div class="col-lg-4">
				Navigation Pane
			</div>
			<div class="col-lg-4">
				<div style="width: 100px; height: 100px; border: solid 2px green;">
				</div>
				<form action="index.php" method="post">
					<input type="file" class="custom-file-input">
					<label class="custom-file-label" for="customFile">Choose Profile Picture</label>
					<button type="submit">Update Profile Picture</button>
				</form>
			</div>
			<div class="col-lg-4">
				<form action="index.php" method="post">
					<div class="form-group">
						<input type="text" class="form-control form-control-sm" name="firstname" id="firstname" placeholder="First Name">
					</div>
					<div class="form-group">
						<input type="text" class="form-control form-control-sm" name="lastname" id="lastname" placeholder="Last Name">
					</div>
					<div class="form-group">
						<input type="number" class="form-control form-control-sm" name="age" id="age" placeHolder="Age">
					</div>
					<fieldset>
						<legend>I am a: </legend>
						<label for="gender-m">Male</label>
						<input type="radio" name="gender" id="gender-m">
						<label for="gender-f">Female</label>
						<input type="radio" name="gender" id="gender-f">
					</fieldset>
					<fieldset>
						<legend>Seeking a: </legend>
						<label for="seeking-m">Male</label>
						<input type="radio" name="seeking" id="seeking-m">
						<label for="seeking-f">Female</label>
						<input type="radio" name="seeking" id="seeking-f">
					</fieldset>
					<div class="form-group">
						<label for="description">Description</label>
						<textarea name="description" name="description" id="description" cols="30" rows="10"></textarea>
					</div>

					<button type="submit" class="btn btn-sm btn-outline-primary">Submit Changes</button>
				</form>
			</div>
		</div>
	</div>
</body>
</html>