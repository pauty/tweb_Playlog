
<?php 
include("top.html");
?>
<!-- 
Daniele Pautasso

HTML page to show user's matches
-->
<?php
//we import info for all the singles
$singles=file("singles.txt",FILE_IGNORE_NEW_LINES);

$search_name=trim($_GET["name"]); //name of the user to search for
$usr_like=NULL; //needed in order to avoid notice for uninitialized variable
$found = false;
for($i=0; !$found && $i<count($singles); $i++){
	$data=explode(",",$singles[$i]);
	//control if user has previously specified his preference about partner gender
	if(count($data)==7)
		list($usr_name,$usr_gender,$usr_age,$usr_pers,$usr_OS,$usr_min,$usr_max)=$data;
	else if(count($data)==8)
		list($usr_name,$usr_gender,$usr_age,$usr_pers,$usr_OS,$usr_min,$usr_max,$usr_like)=$data;
	//check if user was found
	$found=(strcmp($usr_name,$search_name)==0);
}
if($found){
	?>
	<h1>Matches for <?= $search_name ?></h1><br>
	<?php
	foreach($singles as $single){
		//get info about a single
		list($name,$gender,$age,$pers,$OS,$min,$max)=explode(",",$single);
		//if the single isn't the user itself
		if(strcmp($usr_name,$name)!=0){
			//we check if we found a match and eventually show it
			if(test_gender($gender,$usr_like,$usr_gender) && test_pers($pers,$usr_pers) && test_age($age,$usr_min,$usr_max) && test_os($OS,$usr_OS)){
				show_match($name,$gender,$age,$pers,$OS);
			}
		}
	}
}
else{
	?>
	<h1>Sorry, no user with name <?= $search_name ?> found</h1>
<?php
}

include("bottom.html");

//tests for compatibility
function test_gender($g,$ul,$ug){
	if(isset($ul))
		return ((strcmp($ul,"B")==0) || (strcmp($ul,$g)==0));
	else 
		return (strcmp($g,$ug)!=0);
}

function test_pers($p,$up){
	$cont=0;
	for($i=0; $i<4; $i++){
		if($p[$i]==$up[$i])
			$cont++;
	}
	return ($cont>0);
}

function test_age($age,$min,$max){
	return ($age>=$min && $age<=$max);
}

function test_os($os,$uos){
	return (strcmp($os,$uos)==0);
}

//print HTML code for a match
function show_match($n,$g,$a,$p,$os){
	?>
	<div class="match">
			<ul>
				<li><img src="http://www.cs.washington.edu/education/courses/cse190m/12sp/homework/4/user.jpg" alt="match pic"></li>
				<li><p><?= $n ?></p></li>
				<li><strong>Gender:</strong><?= $g ?></li>
				<li><strong>Age:</strong><?= $a ?></li>
				<li><strong>Type:</strong><?= $p ?></li>
				<li><strong>OS:</strong><?= $os ?></li>
			</ul>
	</div>
	<?php
}
?>