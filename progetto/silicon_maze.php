<?php

session_start();

?>

<!DOCTYPE html>

<!--
###################################
The interactive page. Here te user can play a simple game
made in JavaScript.
###################################
-->

<html lang="en">
	<head>
		<title>Silicon Maze</title>
		<meta charset="utf-8" />
		
		<link rel="icon" href="img/default/icon.png">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
		<link href='//fonts.googleapis.com/css?family=Geostar Fill' rel='stylesheet'>
		<link href="css/silicon_maze.css" type="text/css" rel="stylesheet" />

		<script src="http://ajax.googleapis.com/ajax/libs/prototype/1.7.0.0/prototype.js" type="text/javascript"></script>
		<script src="js/silicon_maze.js" type="text/javascript"></script>

	</head>

	<body>
		<?php
		include("header.php");
		?>

		<div id="playlog_container">
			<div id=game_wrapper>
				<div id="info_panel">
					<div id="level"></div>
					<div id="timer"></div>
					<div id="score"></div>
				</div>
				<div id="left_panel" class="animated fadeInLeft">
					<div id="next_squares"></div>
					<button id="ff_button" title="FAST FORWARD"><i class="fa fa-fast-forward" aria-hidden="true"></i></button>
				</div>
				<div id="game_panel" class="animated fadeInUp">
					<div id="text_panel">
						<p id="main_title">Silicon Maze</p>
						<h2>How to play</h2>
						<hr>
						<p>
							Match the beginning of the circuit (<span class="yellow">yellow</span> pin) with its end 
							(<span class="green">green</span> pin) by clicking on the squares and building a path with the pieces you are given. Be as fast as you can, because when the setup time runs out electricity will start flowing trough the board. If electricity reaches a dead end, game is over.
						</p>
						<p>
							The longer your path, the higher your score: as a bonus, building a complete loop will reward you with three times the standard points. Try to beat your own record. Good luck!
						</p>
							<button id="start_game_button">START</button> 

					</div>
				</div>	
			</div>	
		</div>
	</body>
</html>