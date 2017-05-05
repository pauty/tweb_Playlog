<?php

require_once(__DIR__."/../search/search_functions.php");

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
	case "search_games_by_title":
		$res = search_games_by_title($_POST["gametitle"]);
		if(isset($res)){
			if(!empty($res))
				$res[] = array('success' => 1);
			else 
				$res[] = array('success' => 0);
		}
		else $res = array( '0' => array('success' => -1) );
		break;
	case "search_users_by_name":
		$res = search_users_by_name($_POST["username"]);
		if(isset($res)){
 			if(!empty($res))
 				$res[] = array('success' => 1);
			else 
				$res[] = array('success' => 0);
		}
		else $res = array( '0' => array('success' => -1) );
		break;
	default:
		break;
}

echo json_encode($res);  // output the response for ajax request

?>