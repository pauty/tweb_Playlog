<?php 
//result page for films with Kevin Bacon 

session_start();
/*if the user hasn't logged in, we
redirect him to index page*/
if(!isset($_SESSION["username"])){
	header("Location: index.php");
    die;
}

include("common.php");
include("top.php");

$db = connect_database();

if(isset($db)){
	try {
		$firstname = $_GET["firstname"];
		$lastname = $_GET["lastname"];
		/*prepare and execute the query*/
		$rows = $db->prepare("select movies.name, movies.year 
			                  from actors join roles on actors.id=roles.actor_id join movies on roles.movie_id=movies.id
			                  where actors.id in (select min(id)
			                                     from actors
			                                     where first_name like :name
			                                     and last_name like :surname
			                                     and film_count in (select max(film_count)
			                                                        from actors
			                                                        where first_name like :name
			                                                        and last_name like :surname))
			                  and movies.id in (select movies.id
			                                    from movies join roles on movies.id=roles.movie_id join actors on roles.actor_id=actors.id
			                                    where actors.first_name like 'Kevin' and actors.last_name like 'Bacon')
			                  order by movies.year desc, movies.name asc;");
		$rows->bindValue(':name',$firstname.'%');
		$rows->bindValue(':surname',$lastname);
		$rows->execute();
		/*eventually show the result*/
		show_table($firstname,$lastname,$rows,2);
	} catch(PDOException $ex) {
	?>
	<p>Sorry, a database error occured while quering. Try again later.</p>
	<p>More details: <?= $ex->getMessage() ?></p>
	<?php 
	}
}

include("bottom.html");
?>