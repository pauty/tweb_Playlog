<?php

require_once(__DIR__."/../users/profile.php");

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
	case "get_games_list":
		$res = get_games_list($_POST["profileid"],$_POST["listname"],0);
		if(isset($res)){
		//insert two additional fields to return the list name and if user can edit it
			$res[]= array( 'listname' => $_POST["listname"]);
				if(isset($_SESSION["id"]) && $_SESSION["id"]==$_POST["profileid"]) //user can edit only its own profile
				$res[] = array('edit' => true);
			else
				$res[] = array('edit' => false);
		}
		break;
	case "get_users_list":
		$res = get_users_list($_POST["profileid"],$_POST["listname"],0);
		if(isset($res)){
		//insert two additional fields to return the list name and if user can edit it
			$res[]= array( 'listname' => $_POST["listname"]);
				if(isset($_SESSION["id"]) && $_SESSION["id"]==$_POST["profileid"]) //user can edit only its own profile
				$res[] = array('edit' => true);
			else
				$res[] = array('edit' => false);
		}
		break;
	default:
		break;
}

echo json_encode($res);  // output the response for ajax request

?>