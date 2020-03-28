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

	<link rel="stylesheet" href="webstyle.css">
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
						<input type="submit" class="btn" value="Register">
					</form>	
					<div class = "registerinfo">
						<label class = "registertext">Already have an account? </label>
						<a href="./login.php">Login</a>
					</div>	
				</div>
			</div>

			<div id="formbox" class="contentbox">
				<div class="browsebox">
				<h1 class="headertext">Browse</h1>
					<form id="form" action ="" method="POST">
						<div>
							<select class="optionbox">
								<option style="color: rgb(218, 218, 218)" class= "dropdownfade" value="Gender" disabled selected>LOAD FROM DATABASE?</option>
								<option value="male"> x </option>
								<option value="female"> y </option>
							</select>
						</div>

						<div>
							<select class="optionbox">
								<option style="color: rgb(218, 218, 218)" class= "dropdownfade" value="Gender" disabled selected>LOAD FROM DATABASE?</option>
								<option value="male"> x </option>
								<option value="female"> y </option>
							</select>
						</div>

						<div>
							<select class="optionbox">
								<option style="color: rgb(218, 218, 218)" class= "dropdownfade" value="Gender" disabled selected>LOAD FROM DATABASE?</option>
								<option value="male"> x </option>
								<option value="female"> y </option>
							</select>
						</div>
		
						<div>
							<input class="field" type="text" placeholder="INT AGE" id="age" name="age" value=""><br>
						</div>

						<div >
							<button type="submit" class="btn" name="save">Find Users(no logic)</button>
						</div>
					</form>
				</div>
			</div>

		</div>
	</div>
</body>
</html>