<?php

require_once(__DIR__."/../db_connection/local.php");

function get_username($user_id){
	$res = null;
	try{
		$db = connect_database();
		if(isset($db) && isset($user_id)){
			$username = $db->prepare("select username from users where id = :user_id ");
			$username->bindValue(':user_id', $user_id);
			$username->execute();
			$temp = ($username->fetch());
			$res = $temp["username"];
		}
	}
	catch(PDOException $e){
		$res = null;
		print $e->getMessage();
	}
	return $res;
}

/*return an array of games if list specified by 'listname' is not empty for user specified by 'user_id'
  return an empty array if such list empty
  return null if an error occurred
*/
function get_user_games_list($user_id,$listname,$limit){
	$res = null;
	try{
		$db = connect_database();
		if(isset($db) && isset($user_id) && isset($listname) && isset($limit)){
			 switch($listname){
				case "all":
				  $games = $db->prepare("select games.id, games.title, games.cover_url, ownership.state, ownership.platforms
							 from games join ownership on (games.id = ownership.game_id)
							 where ownership.user_id = :user_id and (ownership.state = 'owned' or ownership.state = 'playing' or ownership.state = 'finished' or ownership.state = 'dropped') 
							 order by games.title
							 limit :l");
				  break;
				default:
					$games = $db->prepare("select games.id, games.title, games.cover_url, ownership.state, ownership.platforms 
							 from games join ownership on (games.id = ownership.game_id)
							 where ownership.user_id = :user_id and ownership.state = :listname
							 order by games.title
							 limit :l");
					$games->bindValue(':listname',$listname);
					break;
			 }
			 $games->bindValue(':user_id', $user_id);
			 if($limit<=0)
				$limit=999;
			 $games->bindValue(':l',$limit, PDO::PARAM_INT);
			 $games->execute();
			 if($games->rowCount()>0)
				 $res = $games->fetchAll(PDO::FETCH_ASSOC);
			 else
			 	$res = array();
		}
	}
	catch(PDOException $e){
		$res = null;
		//print $e->getMessage();
	}
  return $res;
}

?>