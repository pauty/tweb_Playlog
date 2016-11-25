<?php 
include("top.html");
?>
<!-- 
Daniele Pautasso

HTML page to save signup informations
-->
<?php 
$data=array();

//gather all the signup info
$data[0]=$_POST["username"];
$data[1]=$_POST["gender"];
$data[2]=$_POST["age"];
$data[3]=strtoupper($_POST["pers"]);
$data[4]=$_POST["OS"];
$data[5]=$_POST["min_age"];
$data[6]=$_POST["max_age"];
$data[7]=$_POST["likes"];

//and store them
file_put_contents("singles.txt", "\n".(implode(",", $data)), FILE_APPEND);
?>

<h1>Thank You!</h1>
<p>
	Welcome to NerdLuv, <?= $data[0] ?>!<br><br>
	Now <a href="matches.php">log in too see your matches!</a>
</p>

<?php 
include("bottom.html");
?>