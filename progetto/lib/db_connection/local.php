<?php

/*try to connect to the local database*/
function connect_database(){
	$db = null;
	$dbinfo = "mysql:dbname=playlog;host=127.0.0.1;";
	$username = "root";
	$password = "";
	$db = new PDO( $dbinfo, $username, $password );
	if(isset($db))
	 $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	return $db;
}

?>