<?php

/*
This file contains the search procedures for both games (in remote database)
and users (in local database). Results are returned as associative arrays.
*/

require_once(__DIR__."/../db_connection/remote.php");
require_once(__DIR__."/../db_connection/local.php");

function search_games_by_title($title){
	$res = null;
	if(isset($title)){
		$getdata = http_build_query(
			array(
				'fields' => 'name,cover.url,rating',
				'limit' => '30',
				'search' => $title
			)
		);
		$context = get_context($getdata);
		$get_res = @file_get_contents('https://igdbcom-internet-game-database-v1.p.mashape.com/games/', false, $context);
		if($get_res!=false)
			$res = json_decode($get_res, true);
	}
	return $res;
}

function search_users_by_name($name){
	$res = null;
	if(isset($name)){
		try{
		  $db = connect_database();
		  if(isset($db)){
			 $users = $db->prepare("select users.id, users.username from users where users.username like :name order by users.username");
			 $users ->bindValue(":name","%".$name."%");
			 if($users->execute())
				return $users->fetchAll(PDO::FETCH_ASSOC);
		  }
		}
		catch(PDOException $e){
			$res = null;
			//print $e->getMessage();
		}
	}
  return $res;
}

?>