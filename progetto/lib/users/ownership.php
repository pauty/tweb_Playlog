<?php

require_once(__DIR__."/../db_connection/local.php");

/*
This file contains all the functions that are used to update the
ownership table in local database, or to get informations from it.
In addition, functions needed to maintain a local "cache" of games information
are also included.
*/

/*
on success, return an associative array containing ownership state (and eventually platforms) 
for the specified user and game ids;
on generic failure, return null
*/
function get_ownership($user_id,$game_id){
  $res = null;
  try{
	  $db = connect_database();
	  if(isset($db) && isset($user_id) && isset($game_id)){
			$game = $db->prepare("select state , platforms
										 from ownership 
										 where user_id = :user_id and game_id = :game_id ");
			$game->bindValue(':user_id',$user_id);
			$game->bindValue(':game_id',$game_id);
			$game->execute();
			if($game->rowCount()==1){
				$row = $game->fetch();
				$res = array( 'state' => $row["state"], 'platforms' => $row["platforms"] );
			}
			else
				$res = array( 'state' => "none" );
		}
  	}
  	catch(PDOException $e){
  		$res = null;
  		//print $e->getMessage();
  	}
  return $res;
}

/*
return true if update/insert/delete was successful, false otherwise.
	/////////////////////////////////
	!! - IMPORTANT NOTE:   
	/////////////////////////////////
	In manage_ownership() and quick_manage_ownership()
	by "successful" we do not mean a row was actually affected;
	it means only the query was executed whitout errors.
	This ensures that even if the user opens multiple tabs and start
	adding/moving/removing a single game from his lists he will never get 
	a visible error. The database will simply mantain the most recent update
	that is consistent with its internal state.
	For example, in the profile page, moving a game that was previuosly
	removed from one list to another will simply have no effect at all on the 
	database. The will game disappear from user's current list
	as usual, but such game will no longer be present in any
	other list (since it was already removed). 
*/
function manage_ownership($user_id,$game_id,$title,$cover,$state,$platforms){
  $res = false;
  if(isset($user_id) && isset($game_id) && isset($title) && isset($platforms) && isset($state) && valid_ownership($state)){
  		try{
			$db = connect_database();
			if(isset($db)){
			 	/*check if the game specified by game_id already exists in local database*/
				$game = $db->prepare("select * from games where id = :game_id");
				$game->bindValue(':game_id',$game_id);
				$game->execute();
				if($game->rowCount() < 1){  
					//game not exists in local database, insert it for future quick ownership update 
					$ok = game_insert($db, $game_id, $title, $cover);
				}
				else{ 
					//game exists in local database: update title and cover url fields (useful for possible title/url changes)
					$ok = game_update($db, $game_id, $title, $cover);
				}
				//if game insert/update was not successfull, immediatly return with error
				if(!$ok)
					return false;

				/*proceed to update the database according to mew ownership state*/
				if($state!="remove"){
					/* check if an old ownership relation beetween user and game already exists */
					$old_ownership = $db->prepare("select * from ownership where user_id = :user_id and game_id = :game_id");
					$old_ownership->bindValue(":user_id",$user_id);
					$old_ownership->bindValue(":game_id",$game_id);
					$old_ownership->execute();
					if($old_ownership->rowCount() == 1){ 
						//ownership relation already exists: simply update it
						$res = ownership_update($db, $user_id, $game_id, $state, $platforms);
					}
					else{
						//ownership relation does not exists: insert it
						$res = ownership_insert($db, $user_id, $game_id, $state, $platforms);
					}
				}
				else
				{
					/* delete the ownership relation between user and game */
					$res = ownership_delete($db, $user_id, $game_id);
				}
			}
		}
	 	catch (PDOException $e){
	 		$res = false;
	 		//print $e->getMessage();
	 	}
	}
	return $res;
}

/*
update the ownership state for the game identified by game_id and the user identified by user_id
(we assume that an ownership relation was already created using the function manage_ownership previously defined)
return true if update was successful, false otherwise
NOTE: this version of the manage ownership function is used only in user profile
      to move games form one list to another one, or to remove them from any list.  
*/
function quick_manage_ownership($user_id,$game_id,$state){
	$res = false;
	if(isset($user_id) && isset($game_id) && isset($state) && valid_ownership($state)){
		try{
			$db = connect_database();
			if(isset($db)){
				if($state!="remove"){
					/*update the ownership relation according to state parameter - implicit null platforms*/
					$res = ownership_update($db, $user_id, $game_id, $state);
				}
				else{
					/*delete ownership relation*/
					$res =  ownership_delete($db, $user_id, $game_id);
				}
			}
		}
		 catch(PDOException $e){
			$res = false;
		}
	}
	return $res;
}

/*
check if ownership name is valid
*/
function valid_ownership($ownership_name){
	return ( $ownership_name == "owned" ||
				$ownership_name == "playing" ||
				$ownership_name == "finished" ||
				$ownership_name == "dropped" ||
				$ownership_name == "wishlist" ||
				$ownership_name == "remove");
}

function game_insert($db, $game_id, $title, $cover){
	$insert = $db->prepare("insert into games values ( :game_id , :title , :cover )");
	$insert->bindValue(":game_id", $game_id);
	$insert->bindValue(":title", $title);
	$insert->bindValue(':cover', !isset($cover) ? NULL : $cover, PDO::PARAM_STR);
	return $insert->execute();
}

function game_update($db, $game_id, $title, $cover){
	$update = $db->prepare("update games set title = :title , cover_url = :cover  where id = :game_id");
	$update->bindValue(":title", $title);
	$update->bindValue(":game_id", $game_id);
	$update->bindValue(':cover', !isset($cover) ? NULL : $cover, PDO::PARAM_STR);
	return $update->execute();
}

function ownership_insert($db, $user_id, $game_id, $state, $platforms){
	$insert = $db->prepare("insert into ownership values ( :user_id, :game_id, :state, :platforms )");
	$insert->bindValue(":user_id", $user_id);
	$insert->bindValue(":game_id", $game_id);
	$insert->bindValue(":state", $state);
	$insert->bindValue(":platforms", $platforms);
	return $insert->execute();
}

function ownership_update($db, $user_id, $game_id, $state, $platforms = null){
	if(isset($platforms)){ 
		$update = $db->prepare("update ownership set state = :state, platforms = :platforms where user_id = :user_id and game_id = :game_id");
		$update ->bindValue(":platforms", $platforms);
	}
	else{
		//quick_manage_ownership never updates platforms
		$update = $db->prepare("update ownership set state = :state where user_id = :user_id and game_id = :game_id");
	}
	$update ->bindValue(":user_id", $user_id);
	$update ->bindValue(":game_id", $game_id);
	$update ->bindValue(":state", $state);
	return $update ->execute();
}

function ownership_delete($db, $user_id, $game_id){
	$delete = $db->prepare("delete from ownership where user_id = :user_id and game_id = :game_id");
	$delete->bindValue(":user_id", $user_id);
	$delete->bindValue(":game_id", $game_id);
	return $delete->execute();
}

?>