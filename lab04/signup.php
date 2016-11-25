
<?php 
include("top.html");
?>
<!-- 
Daniele Pautasso

HTML page to acquire signup informations
-->
<form action="signup-submit.php" method="post">
	<fieldset>
		<legend>New User Signup:</legend>
		<div class="spacing">
			<strong>Name:</strong><input type="text" name="username" size="16" required />
		</div>
		<div class="spacing">
			<strong>Gender:</strong><label><input  type="radio" name="gender" value="M" />Male</label>
			<label><input type="radio" name="gender" value="F" checked="checked">Female</label>
		</div>
		<div class="spacing">
			<strong>Age:</strong><input type="text" name="age" size="6" maxlength="2" pattern="[0-9]{2}" required/>
		</div>
		<div class="spacing">
			<strong>Personality type:</strong><input type="text" name="pers" size="6" maxlength="4" minlength="4" required/> (<a href="http://www.humanmetrics.com/cgi-win/JTypes2.asp">Don't know your type?</a>)
		</div>
		<div class="spacing">
			<strong>Favorite OS:</strong>
				<select name="OS">
					<option selected="selected">Linux</option>
					<option>Windows</option>
					<option>Mac OS</option>
				</select>
		</div>
		<div class="spacing">
			<strong>Seeking age:</strong><input type="text" name="min_age" placeholder="min" size="6" maxlength="2" pattern="[0-9]{1,2}" /> to <input type="text" name="max_age" placeholder="max" size="6" maxlength="2" pattern="[0-9]{1,2}"/>
		</div>
		<div class="spacing">
			<strong>I like:</strong><label><input  type="radio" name="likes" value="M" />Men</label>
			<label><input type="radio" name="likes" value="F" checked="checked">Women</label>
			<label><input type="radio" name="likes" value="B">Both</label>
		</div>
		<div class="spacing">
			<input type="submit" value="Sign Up">
		</div>

	</fieldset>
</form>
<?php
include("bottom.html");
?>