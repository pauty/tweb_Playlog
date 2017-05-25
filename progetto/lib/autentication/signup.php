<?php

require_once(__DIR__."/../db_connection/local.php");

/*
This file contains all the functions that are needed 
to allow an user to sign up
*/

/* 
return true if signup informations provided are valid, false otherwise
*/
function valid_signup($email,$username,$password){
	if(isset($email) && isset($username) && isset($password)){
		return ( preg_match("/^[a-zA-Z0-9_\-]+@(([a-zA-Z0-9_\-])+\.)+[a-zA-Z]{2,4}$/", $email) 
					&& preg_match("/^[a-zA-Z0-9][a-zA-Z0-9_\-]{4,18}[a-zA-Z0-9]$/", $username)
					&& preg_match("/^(?=.*[a-zA-Z])(?=.*\d).{6,20}$/", $password) );
	}
	return false;
}

/* 
return 1 if user registration completed successfully
return 0 if the email is already associated with a existing user
return -1 in case of generic server side error
*/
function user_signup($email,$username,$password){
	$res = -1;
	if(valid_signup($email,$username,$password)){
		try{
			$db = connect_database();
			if(isset($db)){
				$test = $db->prepare("select * from users where email = :email");
				$test->bindValue(":email", $email);
				$test->execute();
				if($test->rowCount()==0){  //test if no user with specified email already exists
					$password = md5($password);
					$insert = $db->prepare("insert into users values ( default, :email, :username, :password)");
					$insert->bindValue(":email",$email);
					$insert->bindValue(":username", $username);
					$insert->bindValue(":password", $password);
					if($insert->execute())
						$res = 1;
				}
				else $res = 0; 
			}
		}
		catch(PDOException $e){
				$res = -1;
				//print $e->getMessage();
		}
	}
	return $res;
}

?>