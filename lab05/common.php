<?php
function connect_database(){
	$db = null;
	try {
		$db = new PDO("mysql:dbname=imdb;host=127.0.0.1;", "root", "");
		if(isset($db))
			$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	} catch(PDOException $ex) {
		?>
		<p>Sorry, could not connect to database. Please try again later.</p>
		<p>More details: <?= $ex->getMessage() ?></p>
		<?php
	}
	return $db;
}

function check_password($username,$password){
	$db = null;
	$ok = false;
	$db = connect_database();
	if(isset($db)) {
		try {	
			$res = $db->prepare("select * from user where username = :usrname and password = :pswd");
			$res->bindValue(':usrname',$username);
			$res->bindValue(':pswd',$password);
			$res->execute();
			$ok=($res->rowCount()==1);
		} catch(PDOException $ex) {
			?>
			<p>Sorry, a database error occurred. Please try again later.</p>
			<p>More details: <?= $ex->getMessage() ?></p>
			<?php
		}
	}
	return $ok;
}

function show_logout(){
	if(isset($_SESSION["username"])){
	?>
	<form class="logout_button" action="index.php" method="get">
       <input type="submit" name="logout" value="Logout">
	</form>
	<?php
    }
}

function show_table($firstname,$lastname,$rows,$searchtype){
	if($searchtype==1)
		$captionv="All films";
	else
		$captionv="Films with $firstname $lastname and Kevin Bacon";

	if ($rows->rowCount()==0){
		?>
		<h1>No results for <?= $firstname ?> <?= $lastname ?></h1>
		<?php
	}
	else {
		?> 
		<h1>Results  for <?= $firstname ?> <?= $lastname ?></h1>
		<table>
			<caption><?= $captionv ?></caption>
			<tr>
				<th>#</th>
				<th>Title</th>
				<th>Year</th>
			</tr>
			<?php
			$i=1;
			foreach ($rows as $row) {
				?>
				<tr>
					<td><?= $i ?></td><td><?= $row["name"] ?></td><td><?= $row["year"] ?></td>
				</tr>
				<?php	
				$i++; 
			}
			?>
	</table>
	<?php
	}
}
?>