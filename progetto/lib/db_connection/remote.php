<?php

/*build the context needed to connect to the remote database*/
function get_context($getdata){

  $opts = array(
	 'http'=>array(
		'method'=> "GET",
		'header'=> "Accept: application/json\r\n" .
					  "Content-type: application/x-www-form-urlencoded\r\n" .
					  "X-Mashape-Key: NdBCY6MRAJmshXsQxtGiedCrXCxSp1ZspfUjsndTZ6Uje53A63\r\n" , //the key needed by the remote database
		'content' => $getdata
	 )
  );
  return stream_context_create($opts);
}

?>