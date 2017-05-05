<?php

require_once(__DIR__."/../db_connection/local.php");

/*
create a new entry in the followers table
return true on successful insert, false otherwise
*/
function follow($follower,$followed){
	$res = false;
	try{
		$db = connect_database();
		if(isset($db) && isset($follower) && isset($followed) && ($follower != $followed)){
			$query = $db->prepare("insert into followers values (:follower, :followed)");
			$query->bindValue(':follower', $follower);
			$query->bindValue(':followed', $followed);
			$query->execute();
			$res = true;
		}
	}
	catch(PDOException $e){
		$res = false;
		//print $e->getMessage();
	}
	return $res;
}

/*
delete an existing entry in the followers table
return true on successful delete, false otherwise
*/
function unfollow($follower,$followed){
	$res = false;
	try{
		$db = connect_database();
		if(isset($db) && isset($follower) && isset($followed)){
			$query = $db->prepare("delete from followers where (follower_id = :follower and followed_id = :followed)");
			$query->bindValue(':follower', $follower);
			$query->bindValue(':followed', $followed);
			$res = $query->execute();
		}
	}
	catch(PDOException $e){
		$res = false;
		//print $e->getMessage();
	}
	return $res;
}

/*
 return 1 if 'follower' is following 'followed', 0 otherwise.
 return -1 on generic error
*/
function is_following($follower,$followed){
	$res = -1;
	try{
		$db = connect_database();
		if(isset($db) && isset($follower) && isset($followed)){
			$query = $db->prepare("select * from followers where (follower_id = :follower and followed_id = :followed)");
			$query->bindValue(':follower', $follower);
			$query->bindValue(':followed', $followed);
			$query->execute();
			$res = $query->rowCount();
		}
	}
	catch(PDOException $e){
		$res = -1;
		//print $e->getMessage();
	}
	return $res;
}

?>