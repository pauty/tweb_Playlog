<?php 
include("common.php");

session_start();
/*if the user logged out, we destroy
the previuous session and start a new one */
if(isset($_GET["logout"])){
	session_destroy();
	session_regenerate_id(true);
	session_start();
 }

/*if the user filled the login form,
we save username and password*/
$username = null;
$password = null;
if(isset($_POST["username"]))
	$username=$_POST["username"];
if(isset($_POST["password"]))
	$password=$_POST["password"];

/*if username and password are correct, we
set username in session array*/
if(isset($username) && isset($password)){
	if(check_password($username,$password)){
		$_SESSION["username"]=$username;
	}
}

include("top.php"); 
?>

<h1>The One Degree of Kevin Bacon</h1>
<p>Type in an actor's name to see if he/she was ever in a movie with Kevin Bacon!</p>
<p><img src="http://www.cs.washington.edu/education/courses/cse190m/12sp/homework/5/kevin_bacon.jpg" alt="Kevin Bacon" /></p>

<?php 
/*if the user has logged in, we show the search fields;
otherwise we ask to log in, showing the login form*/
if(isset($_SESSION["username"])){
	?>
	<div class="welcome">Welcome back, <?= $_SESSION["username"] ?></div>
	<?php
	include("bottom.html"); 
} else {
	if(isset($username) || isset($password)){ //the user tried to login
		?>
		<div class="login_error">Incorrect username and/or password</div>
		<?php
	}
	else{ //the user just opened index page
 		?>
		<div class="login_alert">You must be logged in to search</div>
		<?php
	}
	include("login.html");
}
?>
