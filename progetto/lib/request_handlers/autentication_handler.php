<?php

require_once(__DIR__."/../autentication/login.php");
require_once(__DIR__."/../autentication/signup.php");

session_start();

if(isset($_POST["function"])){
	$function = $_POST["function"];
}
else{
	http_response_code(404); 
	die();
}
$res = null;

switch($function){
	case "user_login":
		$ok = user_login($_POST["email"],$_POST["password"]);
 		if(!$ok)
			$res = array('success' => 0);
		else if($ok > 0)
			$res = array('success' => 1);
		else
			$res = array('success' => -1);
		break;
	case "user_signup":
		$ok = user_signup($_POST["email"],$_POST["username"],$_POST["password"]);
		if(!$ok)
			$res = array('success' => 0);
 		else if($ok > 0)
			$res = array('success' => 1);
		else
			$res = array('success' => -1);
		break;
	default:
		break;
}

echo json_encode($res);  // output the response for ajax request

?>