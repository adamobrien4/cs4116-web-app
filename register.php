<?php

include "./includes/login_check.php";

login_check(0);

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Register / Simple Browse</title>

	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body>
	<div class="page-header text-center">
		<h1>DatingSucks</h1>
	</div>
	<div class="container-fluid">
		<div class="row text-center">
			<div class="col-lg-6 col-md-6 col-md-6 col-xs-12">
				<form action="./handlers/register_handler.php" method="post" class="col-6 mx-auto">
					<div class="form-group">
						<input type="text" class="form-control form-control-sm" name="firstname" placeholder="First Name">
					</div>
					<div class="form-group">
						<input type="text" class="form-control form-control-sm" name="lastname" placeholder="Last Name">
					</div>
					<div class="form-group">
						<input type="email" class="form-control form-control-sm" name="email" placeholder="Email">
					</div>
					<div class="form-group">
						<input type="password" class="form-control form-control-sm" name="psw1" placeholder="Password">
					</div>
					<div class="form-group">
						<input type="password" class="form-control form-control-sm" name="psw2" placeholder="Password Repeat">
					</div>

					<input type="submit" class="col-10 btn btn-sm btn-outline-primary" value="Register">
				</form>
			</div>
			<div class="col-lg-6 col-md-6 col-md-6 col-xs-12">
				<div class="jumbotron">
					Browse results area
				</div>
			</div>
		</div>
	</div>
</body>
</html>