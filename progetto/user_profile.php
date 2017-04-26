<?php
require_once("lib/users/profile.php");

session_start();

if(isset($_GET["id"]))
	$profile_id = $_GET["id"];
else{
	header("Location: index.php");
    die;
}

$username=get_username($profile_id);
?>

<!DOCTYPE html>

<html lang="en">
	<head>
		<title><?= $username ?>' s Profile</title>
		<meta charset="utf-8" />

		<link rel="icon" href="img/default/icon.png">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
		<link href="css/user_profile.css" type="text/css" rel="stylesheet" />

		

		<script src="http://ajax.googleapis.com/ajax/libs/prototype/1.7.0.0/prototype.js" type="text/javascript"></script>
		<script src="js/user_profile.js" type="text/javascript"></script>

	</head>

	<body>
		<?php
		include("header.php");
		?>
		<input id="profile_id" type="hidden" value="<?= $profile_id ?>">

		<div id="playlog_container">
			<div id="tab_coloumn">
				<h2>
					<img src="img/default/default_user.png" alt="profile_pic">
					<br><?= $username ?>'s games
				</h2>
				<button id="all_tab" class="tab_button" title="all">
					<i class="fa fa-list" aria-hidden="true"></i><span> All</span>
				</button>
				<button id="owned_tab" class="tab_button" title="owned">
					<i class="fa fa-check-square-o" aria-hidden="true"></i><span> Owned</span>
				</button>
				<button id="playing_tab" class="tab_button tab_selected" title="playing">
					<i class="fa fa-gamepad" aria-hidden="true"></i><span> Playing</span>
				</button>
				<button id="finished_tab" class="tab_button" title="finished">
					<i class="fa fa-trophy" aria-hidden="true"></i><span> Finished</span>
				</button>
				<button id="dropped_tab" class="tab_button" title="dropped">
					<i class="fa fa-thumbs-o-down" aria-hidden="true"></i><span> Dropped</span>
				</button>
				<button id="wishlist_tab" class="tab_button" title="wishlist">
					<i class="fa fa-star-o" aria-hidden="true"></i><span> Wishlist</span>
				</button>
			</div>
			<div id="game_list">
				
			</div>
		</div>
	</body>
</html>
