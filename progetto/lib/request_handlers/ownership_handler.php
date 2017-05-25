<?php

require_once(__DIR__."/../users/ownership.php");

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
	case "get_ownership":
		$res = get_ownership($_SESSION["id"], $_POST["gameid"]);
		break;
	case "manage_ownership":
		if(manage_ownership($_SESSION["id"], $_POST["gameid"], $_POST["gametitle"], $_POST["coverurl"], $_POST["state"], $_POST["platforms"]))
			$res = array('success' => 1 , 'state' => $_POST["state"]);
		else
			$res = array('success' => 0);
		break;
	case "quick_manage_ownership":
		if(quick_manage_ownership($_SESSION["id"], $_POST["gameid"], $_POST["state"]))
			$res = array('success' => $_POST["gameid"]); //as success code, return game id
		else
			$res = array('success' => -1);
		break;
	default:
		break;
}

echo json_encode($res);  // output the response for ajax request

?>