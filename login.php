<?php

include "./includes/login_check.php";

login_check(0);

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Login - Simple Browse</title>

	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body>
	<div class="page-header text-center">
		<h1>DatingSucks</h1>
	</div>
	<div class="container-fluid">
		<div class="row text-center">
			<div class="col-lg-4 col-md-4 col-md-8 col-xs-12">
				<form class="col-10 mx-auto" action="./handlers/login_handler.php" method="post">
					<div class="form-group">
						<input type="email" class="form-control form-control-sm" name="email" placeholder="Email Address">
					</div>
					<div class="form-group">
						<input type="password" class="form-control form-control-sm" name="password" placeholder="Password">
					</div>
					<input type="submit" class="col-12 btn btn-sm btn-outline-primary" value="Login">
				</form>

				<hr>

				<a href="./register.php"><button class="col-10 mx-auto btn btn-sm btn-outline-info ">Register</button></a>
			</div>
			<div class="col-lg-8 col-md-8 col-md-8 col-xs-12">
				<div class="jumbotron">
					Simple Browse
				</div>
			</div>
		</div>
	</div>
</body>
</html>