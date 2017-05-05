<?php

/*
try to connect to the local database
please change dbinfo, username and password values
according to local server settings
*/
function connect_database(){
	$db = null;
	$dbname = "playlog";
	$host = "127.0.0.1";
	$username = "root";
	$password = "";
	$dbinfo = "mysql:dbname=".$dbname.";host=".$host.";";
	$db = new PDO( $dbinfo, $username, $password );
	if(isset($db))
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //for debug
	return $db;
}

?>