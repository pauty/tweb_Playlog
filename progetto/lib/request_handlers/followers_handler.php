<?php

require_once(__DIR__."/../users/followers.php");

session_start();

if(isset($_POST["function"])){
	$function = $_POST["function"];
}
else{
	http_response_code(404); 
	die();
}
$res = null;
$success = false;

switch($function){
	case "follow":
		$success = follow($_SESSION["id"],$_POST["profileid"]);
		break;
	case "unfollow":
		$success = unfollow($_SESSION["id"],$_POST["profileid"]);
		break;
	default:
		break;
}
if($success)
	$res = array('success' => $_POST["profileid"]); //as success code, return target profile id
else
	$res = array('success' => -1);

echo json_encode($res);  // output the response for ajax request

?>