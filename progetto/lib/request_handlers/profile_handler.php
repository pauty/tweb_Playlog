<?php

require_once(__DIR__."/../users/profile.php");

session_start();

$function = $_POST["function"];
$res = null;

switch($function){
	case "get_user_games_list":
		$res = get_user_games_list($_POST["profileid"],$_POST["listname"],0);
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