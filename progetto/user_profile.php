<?php

require_once("lib/users/profile.php");
require_once("lib/users/followers.php");

session_start();

if(isset($_GET["id"]))
	$profile_id = $_GET["id"];
else{
	header("Location: index.php");
    die();
}

/*
get the name of the user with the specified id.
if we get null value, such user does not exist.
*/
$username = get_username($profile_id);
?>

<!DOCTYPE html>

<!--
###################################
User's profile page. 
Users can see other users' profiles
and edit their own lists as long as they are
logged in. From here users can also
follow/unfollow each other.
###################################
-->

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
			<?php
			//check if an user with the specified ID was found
			if($username != null){
				?>
				<div id="tab_coloumn">
					<h1><?= $username ?></h1>
					<img src="img/default/default_user.png" alt="profile pic">
					<?php
					// show the follow/unfollow button 
					if(isset($_SESSION["id"]) && $_SESSION["id"] != $profile_id){
						$follow_flag = is_following($_SESSION["id"],$profile_id);
						if($follow_flag == 0){
							echo '<button id="follow_button" class="follow" value="follow" title="Follow"><i class="fa fa-plus" aria-hidden="true"></i></button>';
						}
						else if ($follow_flag == 1){
							echo '<button id="follow_button" class="unfollow" value="unfollow" title="Unfollow"><i class="fa fa-check" aria-hidden="true"></i></button>';
						}
						else if($follow_flag == -1)
							echo '[ ERROR ]';  //for debug
						//if flag is -1, a server side error occured, and we show no button
					}
					?>
					<div class="group_button group_selected" title="games">
						<h2> Games </h2>
						<div class="group_content">
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
					</div>
					<div class="group_button" title="users">
						<h2> Users </h2>
						<div class="group_content">
							<button id="followed_tab" class="tab_button" title="followed">
								<span>Followed</span>
							</button>
							<button id="following_tab" class="tab_button" title="followers">
								<span>Followers</span>
							</button>
						</div>
					</div>
				</div>
				<div id="game_list">				
				</div>
				<?php
			}
			else{
				//handle an user ID that does not exist in local database
				?>
				<p class="alert_p">Sorry, unable to find a user with the specified ID</p> 
				<?php
			}
			?>
		</div>
	</body>
</html>
