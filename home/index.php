<?php

include_once('../vendor/autoload.php');
\Dotenv\Dotenv::createImmutable('../')->load();

include '../includes/db_conn.php';
include '../includes/login_check.php';
include '../includes/helper_functions.php';

// Allow only logged in users to visit this page
login_check(1);

function get_next_potential_match($db_conn)
{
	// Get a pending connection request for the current user
	$sql = "SELECT IF(TABLE2.userA_id = {$_SESSION['user_id']}, TABLE2.userB_id, TABLE2.userA_id) AS other_user_id, TABLE2.connection_id FROM ( SELECT connection_id, userA_id, userB_id FROM connections WHERE result = 'pending' AND (userA_id = {$_SESSION['user_id']} OR userB_id = {$_SESSION['user_id']}) ) AS TABLE2 LIMIT 1";
	$r = mysqli_query($db_conn, $sql);

	if ($r) {
		if (mysqli_num_rows($r) > 0) {
			$row = mysqli_fetch_assoc($r);

			return get_user_card_data($db_conn, $row['other_user_id'], $row);
		} else {
			// Return potential_match made from algorithm
			$p_sql = "SELECT IF(TABLE2.userA_id = {$_SESSION['user_id']}, TABLE2.userB_id, TABLE2.userA_id) AS other_user_id, TABLE2.id as connection_id, TABLE2.weight FROM ( SELECT id, userA_id, userB_id, weight FROM potential_matches WHERE (userA_id = {$_SESSION['user_id']} OR userB_id = {$_SESSION['user_id']}) ) AS TABLE2 LIMIT 1";
			$p_query = mysqli_query($db_conn, $p_sql);

			if ($p_query) {
				if (mysqli_num_rows($p_query) > 0) {
					$p_row = mysqli_fetch_assoc($p_query);

					return get_user_card_data($db_conn, $p_row['other_user_id'], $p_row);
				}
			}
		}
	}
}

function get_user_card_data($db_conn, $user_id, $connection)
{
	$u_sql = "SELECT users.user_id, users.firstname, users.lastname, profiles.age, profiles.gender, profiles.seeking, profiles.description FROM users INNER JOIN profiles on users.user_id = profiles.user_id WHERE users.user_id = {$user_id} LIMIT 1";
	$u_query = mysqli_query($db_conn, $u_sql);

	if ($u_query) {
		if (mysqli_num_rows($u_query) > 0) {
			$u_row = mysqli_fetch_assoc($u_query);

			$weight = 0;
			$connection['is_request'] = true;
			if(key_exists("weight", $connection)) {
				$weight = ($connection['weight'] / 160) * 100;
				$connection['is_request'] = false;
			}
			$u_row['weight'] = $weight;

			$traits = array();
			$interests = array();

			$traitsql = "SELECT traits.trait_id, available_traits.name, available_traits.icon FROM traits INNER JOIN available_traits ON traits.trait_id = available_traits.trait_id WHERE user_id = {$user_id}";
			$trait_res = mysqli_query($db_conn, $traitsql);

			if ($trait_res) {
				if (mysqli_num_rows($trait_res) > 0) {
					while ($trait_row = mysqli_fetch_assoc($trait_res)) {
						array_push($traits, $trait_row);
					}
				}
			}

			$interestsql = "SELECT interests.interest_id, available_interests.name, available_interests.icon FROM interests INNER JOIN available_interests ON interests.interest_id = available_interests.interest_id WHERE user_id = {$user_id}";
			$interest_res = mysqli_query($db_conn, $interestsql);

			if ($interest_res) {
				if (mysqli_num_rows($interest_res) > 0) {
					while ($interest_row = mysqli_fetch_assoc($interest_res)) {
						array_push($interests, $interest_row);
					}
				}
			}

			$u_row['traits'] = $traits;
			$u_row['interests'] = $interests;
			$u_row['connection'] = $connection;
			return $u_row;
		}
	}
	return false;
}

$u = get_next_potential_match($db_conn);
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

	<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	<!-- Fontawesome Icons -->
	<script src="https://kit.fontawesome.com/3aa3856778.js" crossorigin="anonymous"></script>
	<script src="./home.js"></script>


	<style>
		.home {
			/*CSS to highlight when on current page for navbar*/
			background-color: #ec335a;
			color: white !important;
		}
	</style>

	<script>
		var conn_data = <?php echo json_encode($u['connection']) ?>;
	</script>

</head>

<body>

	<div id="wrapper">
		<?php include_once('../navbar.php'); ?>
		<div class="page-content-wrapper">
			<div class="container-fluid">
				<a class="btn btn-link" role="button" id="menu-toggle" href="#menu-toggle"><i class="fa fa-bars"></i> MENU</a>

				<?php if ($u) {
					echo "<div class='row justify-content-center'>
						<div class='card text-center col-10'>
							<div class='card-body'>
								<img style='width: 125px; height: 125px' src='{$_ENV['site_home']}assets/uploads/{$u['user_id']}.jpg'/>
								<h4 class='card-title' id='card-user-title'>{$u['firstname']}, {$u['age']}</h4>
								<hr />";
								if($u['weight'] > 0) {
									echo "<h6 class='text-muted card-subtitle mb-2'>Your DatingSucks&reg; match score : {$u['weight']}%</h6>";
									echo "<div class='row justify-content-center'>
											<div class='project-progress col-8'>
												<div class='progress'>
													<div role='progressbar' style='width: {$u['weight']}%; height: 16px;' aria-valuenow='{$u['weight']}' aria-valuemin='0' aria-valuemax='100' class='progress-bar bg-red'>
													</div>
												</div>
											</div>
										</div>";
								} else {
									echo "<h6 class='text-muted card-subtitle mb-2'>Has requested to connect with you <strong>DIRECTLY</strong></h6>";
								}

								echo "<div class='container bio' id='user-bio'>
									<p>{$u['description']}</p>
								</div>

								<!--Highlight the interests and traits that are in common-->
								<div class='container'>
									<p style='text-align: left; text-decoration: underline'><b>Interests</b></p>
									<div class='bg-c-green counter-block'>
										<div class='row' id='interests-row'>";
											foreach($u['interests'] as $index => $interest) {
												echo "<div class='col-sm'>
													<i class='fa {$interest['icon']}'></i>
													<p>{$interest['name']}</p>
												</div>";
											}
								echo "</div>
									</div>
								</div>

								<div class='container'>
									<p style='text-align: left; text-decoration: underline'><b>Traits</b></p>
									<div class='bg-c-yellow counter-block'>
										<div class='row' id='traits-row'>";
										foreach($u['traits'] as $index => $trait) {
											echo "<div class='col-sm'>
												<i class='fa {$trait['icon']}'></i>
												<p>{$trait['name']}</p>
											</div>";
										}
								echo "</div>
									</div>
								</div>

								<div style='padding: 10px; margin-top: 20px;'>
									<a class='card-link connect-button' id='accept-request'><i style='color:red' class='fas fa-heart fa-2x grow'></i></a>
									<a class='card-link connect-button' id='decline-request'><i class='fas fa-times-circle fa-2x grow'></i></a>
								</div>
							</div>
						</div>
					</div>";
				 } else {
					echo "<div class='text-center'>
						<h1>You have no match requests!<br><a href='../search/'>Go get <strong>some</strong>.</a></h1>
					</div>";
				} ?>

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