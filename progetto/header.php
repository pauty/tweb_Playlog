<div class="header">
	<div class="header_container">
		<a href="index.php" class="header_logo">
			<div>
				play.log
			</div>
		</a>
		<?php
		//eventually fill the game search field if the corrisponding GET parameter is set
		if(isset($_GET["gametitle"])){
			?>
			<input type="text" id="header_search_gametitle" class="header_search" placeholder="Search games" value="<?= $_GET["gametitle"] ?>">
			<?php
		}
		else{
			?>
			<input type="text" id="header_search_gametitle" class="header_search" placeholder="Search games">
			<?php
		}
		?>
		<button id="header_search_game_button" class="header_search_button">
			<i class="fa fa-search" aria-hidden="true"></i>
		</button>
		<?php
		//eventually fill the user search field if the corrisponding GET parameter is set
		if(isset($_GET["username"])){
			?>
			<input type="text" id="header_search_username" class="header_search" placeholder="Search users" value="<?= $_GET["username"] ?>">
			<?php
		}
		else{
			?>
			<input type="text" id="header_search_username" class="header_search" placeholder="Search users">
			<?php
		}
		?>
		<button id="header_search_user_button" class="header_search_button">
			<i class="fa fa-search" aria-hidden="true"></i>
		</button>
		<?php
		//show either the link to the user's profile and the logout button, or the signup and login buttons
		if(isset($_SESSION["id"]) && isset($_SESSION["username"])){
			?>
			<a href="index.php?logout=t" class="header_control">
				<i class="fa fa-sign-out" aria-hidden="true"></i>
				Log Out
			</a>
			<a href="user_profile.php?id=<?= $_SESSION["id"] ?>" class="header_control">
				<i class="fa fa-user-circle-o" aria-hidden="true"></i>
				<?= $_SESSION["username"] ?>
			</a>
		<?php
		}
		else{
			?>
			<a href="index.php?show_login=t" class="header_control">
				Log In
			</a>
			<a href="index.php?show_signup=t" class="header_control">
				Sign Up
			</a>
		<?php	
		}
		?>
	</div>
</div>