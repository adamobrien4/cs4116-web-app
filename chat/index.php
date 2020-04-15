<?php

include_once('../vendor/autoload.php');
\Dotenv\Dotenv::createImmutable('../')->load();

include '../includes/db_conn.php';
include '../includes/login_check.php';
include '../includes/helper_functions.php';

// Allow only logged in users to visit this page
login_check(1);

$chats = array();

$query = "SELECT 	IF(TABLE2.userA_id = {$_SESSION['user_id']}, TABLE2.userB_id, TABLE2.userA_id) AS other_user_id,
					IF(TABLE2.userA_id = {$_SESSION['user_id']}, 'A', 'B') AS you_are_user,
					TABLE2.chat_id,
					TABLE2.A_last_viewed,
					TABLE2.B_last_viewed
	FROM ( SELECT userA_id, userB_id, chat_id, A_last_viewed, B_last_viewed FROM chats WHERE userA_id = {$_SESSION['user_id']} OR userB_id = {$_SESSION['user_id']} ) AS TABLE2";

$res = mysqli_query($db_conn, $query);
if($res) {
    if(mysqli_num_rows($res) > 0) {
        while($row = mysqli_fetch_assoc($res)){

			// Get last message from A + B for this chat
			$sql = "SELECT * FROM (SELECT TIMESTAMP AS ";
			if($row['you_are_user'] == "A"){
				$sql .= "tstmpA FROM messages WHERE user_id = {$_SESSION['user_id']}";
			} else {
				$sql .= "tstmpB FROM messages WHERE user_id = {$_SESSION['user_id']}";
			}

			$sql .= " AND chat_id = {$row['chat_id']} ORDER BY TIMESTAMP DESC LIMIT 1) AS T1
			JOIN (SELECT TIMESTAMP AS ";

			if($row['you_are_user'] == "A"){
				$sql .= "tstmpB FROM messages WHERE user_id = {$row['other_user_id']}";
			} else {
				$sql .= "tstmpA FROM messages WHERE user_id = {$row['other_user_id']}";
			}

			$sql .= " AND chat_id = {$row['chat_id']} ORDER BY TIMESTAMP DESC LIMIT 1) AS T2";

			echo $sql;

			$rsp = mysqli_query($db_conn, $sql);
			if($rsp){
				if(mysqli_num_rows($rsp) > 0){
					$row2 = mysqli_fetch_assoc($rsp);
					$chats[$row['chat_id']] = array_merge($row, $row2);
				}
			} else {
				// What is chat has no messages yet
				$chats[$row['chat_id']] = $row;
			}

			//$sql = "SELECT * FROM (SELECT TIMESTAMP AS tstmpA FROM messages WHERE user_id = 46 AND chat_id = 5 ORDER BY TIMESTAMP DESC LIMIT 1) AS T1
			//JOIN (SELECT TIMESTAMP AS tstmpB FROM messages WHERE user_id = 13 AND chat_id = 5 ORDER BY TIMESTAMP DESC LIMIT 1) AS T2";

            // array_push($chats, $row);
        }
	}
}

echo(json_encode($chats));

/*
$query = "SELECT chat_id FROM chats WHERE userA_id = {$_SESSION['user_id']} OR userB_id = {$_SESSION['user_id']}";
$res = mysqli_query($db_conn, $query);

if($res) { //handles errors if the query failed
    if(mysqli_num_rows($res) > 0) {
        while($row = mysqli_fetch_assoc($res)){
            array_push($chats, $row);
        }
	}
}
*/
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<title>Chat</title>

	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">

	<link rel="stylesheet" href="../assets/css/Sidebar-Menu-1.css">
	<link rel="stylesheet" href="../assets/css/Sidebar-Menu.css">
	<link rel="stylesheet" href="../assets/css/styles.css">


	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css">

	<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
	<script src="../assets/js/moment.js"></script>
	<script src="../assets/js/moment-tz.js"></script>
	<script src="./chat.js"></script>

	<link rel="stylesheet" href="chat.css" />

	<script>

		var chats = <?php echo(json_encode($chats)); ?>;
		var user_id = <?php echo $_SESSION['user_id']; ?>

		var current_active_chat = -1;

	</script>
</head>


<body>

	<div id="wrapper">
		<?php include('..\navbar.php'); ?>

		<div class="page-content-wrapper">
			<div class="container-fluid bootstrap_chat">
				<a class="btn btn-link" role="button" id="menu-toggle" href="#menu-toggle"><i class="fa fa-bars"></i> MENU</a>


				<div>
					<div class="container py-5 px-4">
						<!-- For demo purpose-->
						<header class="text-center">
							<h1 class="display-4 text-white">Chat -- only works visually for now entering data on the db to see on the ui</h1>
						</header>

						<div class="row rounded-lg overflow-hidden shadow">
							<!-- Users conversations box-->
							<div class="col-5 px-0">
								<div class="bg-white">

									<div class="bg-gray px-4 py-2 bg-light">
										<p class="h5 mb-0 py-1">Recent</p>
									</div>

									<div id="messages-box">
										<div class="list-group rounded-0">

											<?php
											foreach ($chats as $index => $chat) {
												$timestamp = null;
												$notification = "";
												if($chat['you_are_user'] == "A"){
													$timestamp = $chat['B_last_viewed'];
													if($chat['tstmpB'] > $chat['A_last_viewed']){
														$notification = "<span class='badge badge-pill badge-danger'>!</span>";
													}
												} else {
													$timestamp = $chat['A_last_viewed'];
													if($chat['tstmpA'] > $chat['B_last_viewed']){
														$notification = "<span class='badge badge-pill badge-danger'>!</span>";
													}
												}

												echo ("<div class='list-group-item list-group-item-action rounded-0' onclick='getMessages({$chat['chat_id']})'>
														<div class='media'><img src='https://res.cloudinary.com/mhmd/image/upload/v1564960395/avatar_usae7z.svg' alt='user' width='50' class='rounded-circle'>
															<div class='media-body ml-4'>
																<div class='d-flex align-items-center justify-content-between mb-1'>
																{$notification}
																<h6 class='mb-0'>" . $chat['chat_id'] . "</h6><small class='small font-weight-bold'>Last message from them: ". $timestamp ."</small>
																</div>
																<p class='font-italic mb-0 text-small'>Havent decided if I am going to include partial message text here or not.</p>
															</div>
														</div>
													  </div>");
											} ?>

										</div>
									</div>
								</div>
							</div>

							<!-- Chat Box -->
							<div class="col-7 px-0">
								<div class="px-4 py-5 chat-box bg-white" id="chat-box">

									<h3>Select a chat to start chatting</h3>


									<?php /*

									// error somewhere - only putting some values into the chat box
									//call the messages function based on the toggle switch that happens somehwere above

									foreach ($messages as $message) {
										echo ("
											<div class='media w-50 mb-3'> 
												<div class='media-body'>
													<div class='rounded py-2 px-3 mb-2'>
														<p class='text-small mb-0'>" . $message['message'] . "</p>
													</div>
												</div>
											</div>
										");
									}  */ ?>




								</div>

								<!-- Typing area -->
								<div class="bg-light">
									<div class="input-group">
										<input type="text" id="send-chat-field" placeholder="Type a message" aria-describedby="send-chat-button" class="form-control rounded-0 border-0 py-4 bg-light">
										<div class="input-group-append">
											<button id="send-chat-button" class="btn btn-link"> <i class="fa fa-paper-plane"></i></button>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	</div>

	<script src="../assets/js/Sidebar-Menu.js"></script>

</body>

</html>