<?php

require_once(__DIR__."/../db_connection/local.php");

/*on success, return a simple array containing ownership state (and eventually platforms) 
  for the specified user and game id;
  on generic failure, return null*/
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

/*return true if update was successful, false otherwise
*/
function update_ownership($user_id,$game_id,$title,$platforms,$state,$cover){
  $res = false;
  if(isset($user_id) && isset($game_id) && isset($title) && isset($platforms) && isset($state) && valid_ownership($state)){
  		try{
			 $db = connect_database();
			 if(isset($db)){
				$game = $db->prepare("select * from games where id = :game_id");
				$game->bindValue(':game_id',$game_id);
				$game->execute();
				if($game->rowCount()<1){
				  if(isset($cover)){
					 $insert = $db->prepare("insert into games values ( :game_id , :title , :cover )");
					 $insert->bindValue(':cover',$cover);
				  }
				  else
					  $insert = $db->prepare("insert into games values ( :game_id , :title , NULL )");
				  $insert->bindValue(":game_id",$game_id);
				  $insert->bindValue(":title",$title);
				  $insert->execute();
				}
				else if(isset($cover)){
				  $update_cover = $db->prepare("update games set cover_url = :cover where id = :game_id");
				  $update_cover->bindValue(":cover",$cover);
				  $update_cover->bindValue(":game_id",$game_id);
				  $update_cover->execute();
				}
				if($state!="remove"){
				  $old_ownership = $db->prepare("select * from ownership where user_id = :user_id and game_id = :game_id");
				  $old_ownership->bindValue(":user_id",$user_id);
				  $old_ownership->bindValue(":game_id",$game_id);
				  $old_ownership->execute();
				  if($old_ownership->rowCount()==1)
					 $new_ownership = $db->prepare("update ownership set state = :state, platforms = :platforms where user_id = :user_id and game_id = :game_id");
				  else
					 $new_ownership = $db->prepare("insert into ownership values ( :user_id, :game_id, :state, :platforms )");
				  $new_ownership->bindValue(":user_id",$user_id);
				  $new_ownership->bindValue(":game_id",$game_id);
				  $new_ownership->bindValue(":state",$state);
				  $new_ownership->bindValue(":platforms",$platforms);
				  $res = $new_ownership->execute();
				}
				else
				{
				  $old_ownership = $db->prepare("delete from ownership where user_id = :user_id and game_id = :game_id");
				  $old_ownership->bindValue(":user_id",$user_id);
				  $old_ownership->bindValue(":game_id",$game_id);
				  $res = $old_ownership->execute();
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
 set the ownership state for the game specified by game_id
 return true if update was successful, false otherwise
 NOTE: this version of the update ownership function is used only in
 */

function update_ownership_small($user_id,$game_id,$state){
	$res = false;
	if(isset($user_id) && isset($game_id) && isset($state) && valid_ownership($state)){
		try{
			$db = connect_database();
			if($state!="remove"){
				$ownership = $db->prepare("update ownership set state = :state where user_id = :user_id and game_id = :game_id");
				$ownership ->bindValue(":user_id",$user_id);
				$ownership ->bindValue(":game_id",$game_id);
				$ownership ->bindValue(":state",$state);
				$res = $ownership ->execute();
			}
			else{
				$ownership  = $db->prepare("delete from ownership where user_id = :user_id and game_id = :game_id");
				$ownership ->bindValue(":user_id",$user_id);
				$ownership ->bindValue(":game_id",$game_id);
				$res = $ownership ->execute();
			}
		}
		 catch(PDOException $e){
			$res = false;
		}
	}
	 return $res;
}

/*check if ownership is valid */
function valid_ownership($ownership_name){
	return ( $ownership_name == "owned" ||
				$ownership_name == "playing" ||
				$ownership_name == "finished" ||
				$ownership_name == "dropped" ||
				$ownership_name == "wishlist" ||
				$ownership_name == "remove");

}

?>