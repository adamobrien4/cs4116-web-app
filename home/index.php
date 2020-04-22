<?php

include_once('../vendor/autoload.php');
\Dotenv\Dotenv::createImmutable('../')->load();

include '../includes/db_conn.php';
include '../includes/login_check.php';
include '../includes/helper_functions.php';

// Allow only logged in users to visit this page
login_check(1);

$connections = array();

// Get a pending connection request for the current user
$sql = "SELECT IF(TABLE2.userA_id = {$_SESSION['user_id']}, TABLE2.userB_id, TABLE2.userA_id) AS other_user_id, TABLE2.connection_id FROM ( SELECT connection_id, userA_id, userB_id FROM connections WHERE result = 'pending' AND (userA_id = {$_SESSION['user_id']} OR userB_id = {$_SESSION['user_id']}) ) AS TABLE2 LIMIT 1";
$r = mysqli_query($db_conn, $sql);

if ($r) {
	if (mysqli_num_rows($r) > 0) {
		while ($row = mysqli_fetch_assoc($r)) {

			$sql2 = "SELECT users.firstname, users.lastname, profiles.age, profiles.gender, profiles.seeking, profiles.description FROM users INNER JOIN profiles on users.user_id = profiles.user_id WHERE users.user_id = {$row['other_user_id']} LIMIT 1";
			$r2 = mysqli_query($db_conn, $sql2);

			if ($r2) {
				if (mysqli_num_rows($r2) > 0) {
					$row2 = mysqli_fetch_assoc($r2);

					$traits = array();
					$interests = array();

					$traitsql = "SELECT traits.trait_id, available_traits.name, available_traits.icon FROM traits INNER JOIN available_traits ON traits.trait_id = available_traits.trait_id WHERE user_id = {$row['other_user_id']}";
					$trait_res = mysqli_query($db_conn, $traitsql);

					if ($trait_res) {
						if (mysqli_num_rows($trait_res) > 0) {
							while ($trait_row = mysqli_fetch_assoc($trait_res)) {
								array_push($traits, $trait_row);
							}
						}
					}

					$interestsql = "SELECT interests.interest_id, available_interests.name, available_interests.icon FROM interests INNER JOIN available_interests ON interests.interest_id = available_interests.interest_id WHERE user_id = {$row['other_user_id']}";
					$interest_res = mysqli_query($db_conn, $interestsql);

					if ($interest_res) {
						if (mysqli_num_rows($interest_res) > 0) {
							while ($interest_row = mysqli_fetch_assoc($interest_res)) {
								array_push($interests, $interest_row);
							}
						}
					}

					$row2['traits'] = $traits;
					$row2['interests'] = $interests;

					$row['user'] = $row2;
					array_push($connections, $row);
				} else {
					die('error 1');
				}
			} else {
				die('error 2');
			}
		}
	}
}


?>

<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
	<title>Home page</title>

	<link rel="stylesheet" href="styles.css">


	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">


	<link rel="stylesheet" href="../assets/css/Sidebar-Menu-1.css">
	<link rel="stylesheet" href="../assets/css/Sidebar-Menu.css">
	<link rel="stylesheet" href="../assets/css/styles.css">

	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css">
	<link rel="stylesheet" href="search.css">

	<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	<!-- Fontawesome Icons -->
	<script src="https://kit.fontawesome.com/3aa3856778.js" crossorigin="anonymous"></script>
	<script src="./home.js"></script>

	<script>
		var connections = <?php echo (json_encode($connections)); ?>;
	</script>


    <style>
        .home { /*CSS to highlight when on current page for navbar*/
            background-color:#e94b3cff;
            color:white !important;
        }
	</style>

</head>

<body>

	<div id="wrapper">
		<?php include_once('../navbar.php'); ?>
		<div class="page-content-wrapper">
			<div class="container-fluid">
				<a class="btn btn-link" role="button" id="menu-toggle" href="#menu-toggle"><i class="fa fa-bars"></i> MENU</a>

				<div class="alert alert-warning" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<strong>Notice!</strong> You have 5 new potential matches and 2 unread messages!
				</div>

				<?php if(count($connections) > 0) { ?>
					<div class="card text-center">
						<div class="card-body"><img />
							<h4 class="card-title" id="card-user-title">Maggy, 34</h4>
							<h6 class="text-muted card-subtitle mb-2">Limerick</h6>
							<hr />
							<h6 class="text-muted card-subtitle mb-2">DatingSuck's proprietary algorithm says: 87% chance ye two match up well hai</h6>

							<!-- Might look nice to have a circular progress bar-->
							<div class="project-progress">
								<div class="progress">
									<div role="progressbar" style="width: 87%; height: 16px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" class="progress-bar bg-red">
									</div>
								</div>
							</div>

							<!--Might be funny to have a funny quotes from the dating sucks team on things not to say on a first date
			if you match with John dont bring up your third ex wife etc.. etc..
			-->
							<div class="container bio" id="user-bio">
								<p>This is a big bio about me, my name is MM MM and I love dating however dating sucks for a woman like me and so I have enlisted the services of DatingSucks.</p>
							</div>

							<!--Highlight the interests and traits that are in common-->
							<div class="container">
								<div class="bg-c-green counter-block">
									<div class="row" id="interests-row"></div>
								</div>
							</div>
							<!--End of interests section-->

							<div class="container">
								<div class="bg-c-yellow counter-block">
									<div class="row" id="traits-row"></div>
								</div>
							</div>
							<!--End of traits section-->

							<!--should be a form to accept/reject // add color change on hover - space them approprietely -->
							<div style="padding: 10px; margin-top: 20px;">
								<a class="card-link" id="accept-request"><i style="color:red" class="fas fa-heart fa-2x"></i></a>
								<a class="card-link" id="decline-request"><i class="fas fa-times-circle fa-2x"></i></a>
							</div>

						</div>

					</div>
				<?php } else { ?>
					<div class="text-center">
						<h1>You have no match requests!<br><a href="../search/">Go get <strong>some</strong>.</a></h1>
					</div>
				<?php } ?>

			</div>
		</div>
	</div>
	<script src="../assets/js/Sidebar-Menu.js"></script>

	<script>
		window.setTimeout(function() {
			$(".alert").fadeTo(500, 0).slideUp(500, function() {
				$(this).remove();
			});
		}, 4000);
	</script>





</body>

</html>