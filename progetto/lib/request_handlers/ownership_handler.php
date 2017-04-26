<?php

require_once(__DIR__."/../users/ownership.php");

session_start();

$function = $_POST["function"];
$res = null;

switch($function){
	case "get_ownership":
		$res = get_ownership($_SESSION["id"],$_POST["gameid"]);
		break;
	case "update_ownership":
		if(update_ownership($_SESSION["id"],$_POST["gameid"],$_POST["gametitle"],$_POST["platforms"],$_POST["state"],$_POST["coverurl"]))
			$res = array('success' => 1 , 'state' => $_POST["state"]);
		else
			$res = array('success' => 0);
		break;
	case "update_ownership_small":
		if(update_ownership_small($_SESSION["id"],$_POST["gameid"],$_POST["state"]))
			$res = array('success' => $_POST["gameid"]); //as success code, return game id
		else
			$res = array('success' => -1);
		break;
	default:
		break;
}

echo json_encode($res);  // output the response for ajax request

?>