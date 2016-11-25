<?php 
include("top.html");
?>
<!-- 
Daniele Pautasso

HTML page to ask for a user name
-->
<form action="matches-submit.php" method="get">
	<fieldset>
		<legend>Returning User:</legend>
		<div class="spacing">
			<strong>Name:</strong><input type="text" name="name" size="16" required />
		</div>
		<div>
			<input type="submit" value="View My Matches">
		</div>
	</fieldset>
</form>
<br>
<?php
include("bottom.html");
?>
