<?php

require_once("lib/autentication/logout.php");

session_start();

handle_logout();
?>

<!DOCTYPE html>

<!--
###################################
The Home page of the site.
Login and Signup forms are shown on request.
From here it is also possible to search for
games/users, and reach the interactive section
(see 'silicon_maze.php') 
###################################
-->

<html lang="en">
	<head>
		<title>Playlog</title>
		<meta charset="utf-8" />
		
		<link rel="icon" href="img/default/icon.png">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
		<link href="css/index.css" type="text/css" rel="stylesheet" />

		<script src="http://ajax.googleapis.com/ajax/libs/prototype/1.7.0.0/prototype.js" type="text/javascript"></script> 
		<script src="js/index.js" type="text/javascript"></script>

	</head>

	<body id="dynamic_background" class="bkg_<?= rand(0,2) ?>">

		<?php
		/* 
		Here we handle the case user was redirected to index.php after clicking on login/signup buttons in other pages.
		Since we show the login/signup form dinamically using javascript, we simply create an hidden input. 
		On page load the script will check if such hidden input exists, and eventually show the requested form*/
		if(isset($_GET["show_login"])){
			?>
			<input id="show_login" type="hidden">
		<?php
		}
		else if(isset($_GET["show_signup"])){
			?>
			<input id="show_signup" type="hidden">
		<?php
		}
		?>
		
		<div id="playlog_container">
			<div id="left_coloumn">
				<a id="banner" href="index.php">
					<div id=logo class="animated fadeInDown">
						play<span>.</span>log
					</div>
				</a>
				<div id="welcome_area" class="animated fadeInUp">
				<?php
				if(isset($_SESSION["username"]) && isset($_SESSION["id"])) {
				?>	
				<h1>
					Welcome back,<br>
					<a href="user_profile.php?id=<?= $_SESSION["id"] ?>"><?= $_SESSION["username"] ?></a>
					<br>
				</h1>
				<p><br>Not your account? <a href="index.php?logout=t">Log Out</a></p>
				<?php
				}
				else{
				?>
					<h1>Join play.log and never forget to play your favourite games!</h1>
					<p>
						Playlog is an easy way to keep track of all the games you and your friends are currently into.
						It is useful for gamers, perfect for collectors. Simply search for a game and add it to one of your lists. 
					</p>
					<button id="login_button">Log In</button>
					<button id="signup_button">Sign Up</button>	
				<?php
				}
				?>
				</div>
			</div>
			<div id="right_coloumn" class="animated fadeInRight">
				<a href="silicon_maze.php">
					<div id="game_link"><p>Nothing to play? Click here!</p><p><i class="fa fa-exclamation-circle" aria-hidden="true"></i></p></div>
				</a>
				<form action="search.php" method="get">
						<input type="text" name="gametitle" title="search" placeholder="Search for games" required>
						<button class="search_button" type="submit" >
							<i class="fa fa-search" aria-hidden="true"></i>
						</button>
				</form>
				<form action="search.php" method="get">
						<input type="text" name="username" title="search" placeholder="Search for players" required>
						<button class="search_button" type="submit" >
							<i class="fa fa-search" aria-hidden="true"></i>
						</button>
				</form>
			</div>
		</div>	
	</body>
</html>

