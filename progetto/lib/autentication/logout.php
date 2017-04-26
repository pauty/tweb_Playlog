<?php

/*session_start() must be called before this function*/
function handle_logout() {
	/*if the user logged out, we destroy
	the previuous session and start a new one,
	redirecting to home page */
	if(isset($_GET["logout"])){
		session_destroy();
		session_regenerate_id(true);
		header("Location: index.php");
		die;
	 }
}

?>