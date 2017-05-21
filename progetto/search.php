<?php
session_start();
?>

<!DOCTYPE html>

<!--
###################################
The page wich shows search results (both games and users)
Users are redirected here after using the search fields that 
are found in the home page and in the header.
###################################
-->

<html lang="en">
	<head>
		<title>Playlog - Search</title>
		<meta charset="utf-8" />

		<link rel="icon" href="img/default/icon.png">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
		<link href="css/search.css" type="text/css" rel="stylesheet" />

		<script src="http://ajax.googleapis.com/ajax/libs/prototype/1.7.0.0/prototype.js" type="text/javascript"></script>
		<script src="js/search.js" type="text/javascript"></script>

	</head>

	<body>
		<?php
		include("header.php");
		?>
		<div id="playlog_container">

			<div id="result_list">
				
			</div>
		</div>
	</body>
</html>

