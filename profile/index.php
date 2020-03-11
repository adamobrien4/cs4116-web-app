<?php



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
						<input type="text" class="form-control form-control-sm" name="firstname" placeholder="First Name">
					</div>
					<div class="form-group">
						<input type="text" class="form-control form-control-sm" name="lastname" placeholder="Last Name">
					</div>
					<div class="form-group">
						<input type="number" class="form-control form-control-sm" name="age" placeHolder="Age">
					</div>
					<fieldset>
						<legend>Select a Gender: </legend>
						<label for="gender-1">Male</label>
						<input type="radio" name="gender" id="gender-1">
						<label for="gender-2">Female</label>
						<input type="radio" name="gender" id="gender-2">
					</fieldset>
					<fieldset>
						<legend>Seeking: </legend>
						<label for="seeking-1">Male</label>
						<input type="radio" name="seeking" id="seeking-1">
						<label for="seeking-2">Female</label>
						<input type="radio" name="seeking" id="seeking-2">
					</fieldset>
					<div class="form-group">
						<label for="description">Description</label>
						<textarea name="description" name="description" cols="30" rows="10"></textarea>
					</div>

					<button type="submit" class="btn btn-sm btn-outline-primary">Update Changes</button>
				</form>
			</div>
		</div>
	</div>
</body>
</html>