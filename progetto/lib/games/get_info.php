<?php

require_once(__DIR__."/../db_connection/remote.php");

/*return an array containing all the infos about the game with the specified 'id'
  fields that are guaranteed to be filled are: name, platforms, publishers, developers, genres. (eventually, they will assume the 'Unknown' value)
  please manually check the remaining fields: release date, cover, summary, rating, screenshots
  if no game exists with the specified id or the remote request has no success, null is returned instead 
*/
function get_game_info($id){
	$res = null;
	if(isset($id)){
		$getdata = http_build_query( array('fields' => 'name,cover.cloudinary_id,publishers,developers,summary,genres,release_dates,screenshots.cloudinary_id,rating') );
		$context = get_context($getdata);
		$game_json = @file_get_contents('https://igdbcom-internet-game-database-v1.p.mashape.com/games/'.$id, false, $context);
		if($game_json==false) //means that the id is invalid, or the request to the remote database went bad
			return null;

		$games=json_decode($game_json,true);
		$game=$games["0"];

		$getdata = http_build_query( array( 'fields' => 'id,name' ) );
		$context = get_context($getdata);

		$temp = array();
		$plats_ok = false;
		if(isset($game["release_dates"])){
			for($i=0; $i<count($game["release_dates"]); $i++)
				$temp["$i"] = $game["release_dates"]["$i"]["platform"]; //add all the platform ids in temp array
			$temp=array_unique($temp);  //remove duplicates
			$plats_str = implode(",",$temp);
			$plats_json = @file_get_contents('https://igdbcom-internet-game-database-v1.p.mashape.com/platforms/'.$plats_str, false, $context);
			if($plats_json!=false) //if the request has been successful
				$plats_ok = true;
		}
		else{
			$game["release_dates"] = array ( '0' => array( 'human' => "Unknown") );
		}
		if($plats_ok)
			$platforms = json_decode($plats_json,true);
		else
			$platforms = array();
		//add an extra 'Other' platform
		$platforms[] = array( 'name' => "Other") ;

		//set developers info
		$devs_ok = false;
		if(isset($game["developers"])){
		  $devs_str=implode(",",$game["developers"]);
		  $devs_json = @file_get_contents('https://igdbcom-internet-game-database-v1.p.mashape.com/companies/'.$devs_str, false, $context);
		  if($devs_json!=false)
		  		$devs_ok = true;
		}
		if($devs_ok)
		  $developers = json_decode($devs_json,true);
		else
		  $developers= array ( '0' => array( 'name' => "Unknown") );

		//set publishers info
		$pubs_ok = false;
		if(isset($game["publishers"])){
		  $pubs_str=implode(",",$game["publishers"]);
		  $pubs_json = @file_get_contents('https://igdbcom-internet-game-database-v1.p.mashape.com/companies/'.$pubs_str, false, $context);
		  if($pubs_json!=false)
		  		$pubs_ok = true;
		}
		if($pubs_ok)
		  $publishers = json_decode($pubs_json,true);
		else
		  $publishers= array ( '0' => array( 'name' => "Unknown") );

		//set genres info
		$gens_ok = false;
		if(isset($game["genres"])){
		  $gens_str=implode(",",$game["genres"]);
		  $gens_json = @file_get_contents('https://igdbcom-internet-game-database-v1.p.mashape.com/genres/'.$gens_str, false, $context);
		  if($gens_json!=false)
		  		$gens_ok = true;
		}
		if($gens_ok)
		  $genres = json_decode($gens_json,true);
		else
		  $genres= array ( '0' => array( 'name' => "Unknown") );

		//set game cover info
		if(isset($game["cover"])){
			$game["cover"]["url"]="https://images.igdb.com/igdb/image/upload/t_cover_big/".$game["cover"]["cloudinary_id"];
		}

		//build the final array to return
		//unset($game["release_dates"]);
		$game["platforms"]=$platforms;
		$game["developers"]=$developers;
		$game["publishers"]=$publishers;
		$game["genres"]=$genres;

		 $res = $game; //all done!
	}
	return $res;
}

?>