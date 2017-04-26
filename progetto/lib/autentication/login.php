<?php

require_once(__DIR__."/../db_connection/local.php");

/*an integer value is returned, with the following meaning: 
  0  = query to the database was successful, but no match for email/password found
  1  = query to the database was successful, and a match was found (proceed to login)
  -1 = a database error occurred
  -2 = at least one argument was set to null
  -3 = a session was already opened
 */
function user_login($email,$password){
   $res = 0;
	if(!isset($_SESSION["id"]) || !isset($_SESSION["username"])) {
		if(isset($email) && isset($password)){
		   $credentials = check_password($email,$password);
		   if(isset($credentials)){
			  	if(!empty($credentials)){
					$_SESSION["id"] = $credentials["id"];
					$_SESSION["username"] = $credentials["username"];
					$res = 1;
				}
				else $res = 0;
		   }
		  else $res = -1;
		}
		else $res = -2;
	}
	else $res = -3;

	return $res;
}

/*return an array filled with the user credentials if the login was successful (match found)
  return an empty array if no match was found in the database
  return null if a database or invalid argument error occurred
 */
function check_password($email,$password){
	$res = null;
	try{
		$db = connect_database();
		if(isset($db) && isset($email) && isset($password)) {
				$password_hash = md5($password);
				$check = $db->prepare("select * from users where email = :email and password = :pswd");
				$check->bindValue(':email',$email);
				$check->bindValue(':pswd',$password_hash );
				$check->execute();
				$row = $check->fetch();
				if($check->rowCount()==1)
					$res = array( "id" => $row["id"], "username" => $row["username"]);
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