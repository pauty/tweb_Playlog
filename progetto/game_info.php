
<?php

require_once("lib/games/get_info.php");

$user_id=null;
$game_id=null;
if(isset($_GET["id"]))
	$game_id = $_GET["id"];
else{
	header("Location: search.php");
    die;
}
session_start();
if(isset($_SESSION["id"]))
	$user_id = $_SESSION["id"];

$game_info=get_game_info($game_id);

?>

<!DOCTYPE html>

<html lang="en">
	<head>
		<title><?= $game_info["name"] ?></title>
		<meta charset="utf-8" />

		<link rel="icon" href="img/default/icon.png">
		<link rel="icon" href="http://courses.cs.washington.edu/courses/cse190m/11sp/homework/2/rotten.gif">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

		<link href="css/game_info.css" type="text/css" rel="stylesheet" />

		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

		<script src="http://ajax.googleapis.com/ajax/libs/prototype/1.7.0.0/prototype.js" type="text/javascript"></script>
		<script src="js/game_info.js" type="text/javascript"></script> 

	</head>
	<body>

		<input id="game_id" type="hidden" value="<?= $game_info["id"] ?>">
		<input id="cover_url" type="hidden" value="<?= $game_info["cover"]["cloudinary_id"] ?>">

		<?php
		include("header.php");
		?>

		<div id=playlog_container>
			<div id="title_div">
				<h1 id="gametitle"><?= $game_info["name"] ?></h1>
			</div>
			
			<div id="left_coloumn">

				<h3>General Info</h3>
				<hr>
				<dl>
					<dt id="platforms"><strong>Platforms: </strong></dt>
					<dd><?php print_list($game_info["platforms"],"name",", ",1) ?></dd>
					<dt id="developers"><strong>Developers: </strong></dt>
					<dd><?php print_list($game_info["developers"],"name",", ") ?></dd>
					<dt id="publishers"><strong>Publishers: </strong></dt>
					<dd><?php print_list($game_info["publishers"],"name",", ") ?></dd>
					<dt id="genres"><strong>Genres: </strong></dt>
					<dd><?php print_list($game_info["genres"],"name",", ") ?></dd>
					<dt id="release"><strong>Release Date: </strong></dt>
					<dd><?php print_list($game_info["release_dates"],"human",", ",-1) ?></dd>
					<?php
					if(isset($game_info["rating"])){
						?>
						<dt id="rating"><strong>Rating: </strong></dt>
						<dd><?= floor($game_info["rating"]).'/100' ?></dd>
					<?php
					}
					?>
				</dl>

				<?php
				if(isset($game_info["summary"])){
					?>
					<h3>Summary</h3>
					<hr>
					 <p id="summary"><?= $game_info["summary"] ?></p>
					<?php
				}
				?>
			</div>

			<div id="right_coloumn">
				<?php
				if(isset($game_info["cover"])){
					?>
					<img id="cover" src="<?= $game_info["cover"]["url"] ?>" alt="game cover">
					<?php
				}
				else{
					?>
					<img id="cover" src="img/default/cover_not_found.jpg" alt="game cover">
					<?php
				}
			
				if(isset($user_id)){
					?>
					<ul id="platforms_list">
					<?php
					for($i=0; $i<count($game_info["platforms"]); $i++){
						?>
						<li><label><input class="platform_checkbox" type="checkbox" value="<?= $game_info["platforms"][$i]["name"] ?>" /> <?= $game_info["platforms"]["$i"]["name"] ?></label></li>
						<?php
					}
					?>
					</ul>
					<hr>
					<?php
				}
				if(isset($user_id)){
					?>
					<div id="state_buttons">
						
						
					</div>
					<?php
				}
				?>
			</div>	
			

			<?php
			if(isset($game_info["screenshots"])){
				$screenshots_num = count($game_info["screenshots"]);
				?>
				<div id="screenshots_div">
					<h3>Screenshots</h3>
					<hr>
					<div id="screenshotsCarousel" class="carousel slide" data-ride="carousel">
						<!-- Indicators -->
						<ol class="carousel-indicators">
							<li data-target="#screenshotsCarousel" data-slide-to="0" class="active"></li>
							<?php
							for($i=1; $i < $screenshots_num; $i++){
								?>
								<li data-target="#screenshotsCarousel" data-slide-to="<?= $i ?>"></li>
								<?php
							}
							?>
						</ol>

						<!-- Wrapper for slides -->
						<div class="carousel-inner" role="listbox">
							<div class="item active">
								<img src="<?= 'https://images.igdb.com/igdb/image/upload/t_screenshot_big/'.$game_info["screenshots"]["0"]["cloudinary_id"] ?>" alt="screenshot">
							</div>
							<?php
							for($i=1; $i < $screenshots_num; $i++){
								?>
								<div class="item">
									<img src="<?= 'https://images.igdb.com/igdb/image/upload/t_screenshot_big/'.$game_info["screenshots"][$i]["cloudinary_id"] ?>" alt="screenshot">
								</div>
								<?php
							}
							?>
						</div>

						<!-- Left and right controls -->
						<a class="left carousel-control" href="#screenshotsCarousel" role="button" data-slide="prev">
							<i class="fa fa-chevron-left" aria-hidden="true"></i>
						</a>
						<a class="right carousel-control" href="#screenshotsCarousel" role="button" data-slide="next">
							<i class="fa fa-chevron-right" aria-hidden="true"></i>
						</a>
					</div>
				</div>
				<?php 
			} //end if for screenshots
			?>
		</div>

		

	</body>
</html>

<?php

/*- if limiter is a positive number N, the last N values won't be printed (default limiter is 0)
    otherwise, if limiter is a negative number N, only the first N values will be printed
  - separator is a string that will be inserted beetween array values*/ 
function print_list($array,$indexname,$separator,$limiter = 0){
	$len = count($array);
	if($limiter<0)
		$limiter = ($len+$limiter);
	for($i=0; $i<$len-$limiter; $i++){
		echo $array[$i][$indexname];
		if( $i<$len-1-$limiter)
			echo $separator;
	}
}
?>