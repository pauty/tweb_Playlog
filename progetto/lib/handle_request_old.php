<?php

require_once("autentication/login.php");
require_once("autentication/signup.php");
require_once("search/search_all.php");
require_once("users/ownership.php");
require_once("users/profile.php");

session_start();

$function = $_POST["function"];
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