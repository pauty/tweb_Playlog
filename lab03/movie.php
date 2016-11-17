<!DOCTYPE html>

<!--
Daniele Pautasso

Esercizio 3 di Tweb

codice php per generare dinamicamente pagine HTML
contenenti recensioni di diversi film.
-->

<html lang="en">
	<head>
		<title>Rancid Tomatoes</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="icon" href="http://courses.cs.washington.edu/courses/cse190m/11sp/homework/2/rotten.gif">
		<link href="movie.css" type="text/css" rel="stylesheet" />
	</head>

	<body>
		<div id="banner">
			<img src="http://www.cs.washington.edu/education/courses/cse190m/11sp/homework/2/banner.png" alt="Rancid Tomatoes" />
		</div>

		<?php
		#php code for initial setup
		error_reporting(E_ALL | E_STRICT);
		#error_reporting(E_NONE);
		$movie=$_GET["film"];
		$movie_info=file("$movie/info.txt",FILE_IGNORE_NEW_LINES);
		list($movie_title,$movie_year,$movie_score)=$movie_info;
		?>

		<h1><?= $movie_title ?> (<?= $movie_year ?>)</h1>
		
		<div id="container">
			<div id="right_area">
				<div>
					<img src="<?= $movie ?>/overview.png" alt="general overview image" />
				</div>

				<dl>
					<?php
					#read file overview.txt and print overview information
					$overview_fields=file("$movie/overview.txt",FILE_IGNORE_NEW_LINES);
					foreach ($overview_fields as $field){
						#create an array of 2 element exploding a single field on char ':'
						$field_content=explode(":",$field);
						?><dt><?= $field_content[0] ?></dt> 
						<dd><?= $field_content[1] ?></dd>
					<?php
					}
					?>
				</dl>
			</div>

			<div id="left_area">
				<div id="score">
					<?php
					#set the score image according to the score
					if((int)$movie_score >= 60)
						$score_image="freshbig";
					else
						$score_image="rottenbig";
					?><img src="http://www.cs.washington.edu/education/courses/cse190m/11sp/homework/2/<?= $score_image ?>.png" alt="score image" />
					<span><?= $movie_score ?>%</span>
				</div>

				<?php
				#count number of reviews for the film
				$num_reviews=count(glob("$movie/review*.txt"));
				$max_reviews=min(10,$num_reviews);

				#print left coloumn of reviews
				show_coloumn(0,$max_reviews-(int)($max_reviews/2)); 

				#print right coloumn of reviews
				show_coloumn($max_reviews-(int)($max_reviews/2),$max_reviews); 
				?>

			</div>

			<div id="bottom">
				<p>(1-<?=$max_reviews ?>) of <?= $num_reviews ?></p>
			</div>
		</div>

		<div id="validation">
			<a href="http://validator.w3.org/check/referer"><img src="http://www.cs.washington.edu/education/courses/cse190m/11sp/homework/2/w3c-xhtml.png" alt="Validate HTML" /></a><br />
			<a href="http://jigsaw.w3.org/css-validator/check/referer"><img src="http://jigsaw.w3.org/css-validator/images/vcss" alt="Valid CSS!" /></a>
		</div>
	</body>
</html>


<?php
#print HTML code for a coloumn of reviews
function show_coloumn($start,$limit){
	global $num_reviews;
	?>
	<div class="coloumn"> 
		<?php
		for($i=$start; $i<$limit; $i++){  
			if($num_reviews>=10 && $i<9)
				show_review("0".($i+1));
			else
				show_review(($i+1));
		}
		?>
	</div>
	<?php
}

#print HTML code for a single review
function show_review($num){
	global $movie;
	#reads the file review number num
	list($rev,$score,$author,$pub)=file("$movie/review${num}.txt",FILE_IGNORE_NEW_LINES);
	#set the review image according to the review file
	if(strcmp($score,"FRESH")==0)
		$score="fresh";
	else
		$score="rotten";
	?>
	<div class="review">
		<p>
			<img src="http://www.cs.washington.edu/education/courses/cse190m/11sp/homework/2/<?= $score ?>.gif" alt="review image" />
			<q><?= $rev ?></q>
		</p>
		<p>
		   <img src="http://www.cs.washington.edu/education/courses/cse190m/11sp/homework/2/critic.gif" alt="Critic" />
		   <?= $author ?><br />
		   <span class="pub"><?= $pub ?></span>
	   </p>
	</div>
   <?php
}
?>
