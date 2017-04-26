<div class="header">
	<div class="header_container">
		<a href="index.php" class="header_banner">
			<div>
				play.log
			</div>
		</a>
		<?php
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
		if(isset($_SESSION["id"]) && isset($_SESSION["username"])){
			?>
			<a href="index.php?logout=t" class="header_control">
				<div>
					Log Out
				</div>
			</a>
			<a href="user_profile.php?id=<?= $_SESSION["id"] ?>" class="header_control">
				<div>
					<?= $_SESSION["username"] ?>
				</div>
			</a>
		<?php
		}
		else{
			?>
			<a href="index.php?show_login=t" class="header_control">
				<div>
					Log In
				</div>
			</a>
			<a href="index.php?show_signup=t" class="header_control">
				<div>
					Sign Up
				</div>
			</a>
		<?php	
		}
		?>
	</div>
</div>