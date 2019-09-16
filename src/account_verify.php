<?php

	include 'top.php';
	if (isset($usr) && $usr != "") fail("You've already registered!","index.php");
	
	$user_name = mysql_real_escape_string($_GET['user']);
	$user_code = mysql_real_escape_string($_GET['code']);
	
	$check = mysql_query("SELECT userID FROM users WHERE uid = '$user_name' AND account_verify = '$user_code'");
	
	if(mysql_num_rows($check) == 1) {
		// winner!
		$row = mysql_fetch_array($check);
		$userid = $row['userID'];
		mysql_query("UPDATE users SET account_verify = NULL WHERE userID = $userid");

?>

<div class="single-center">
	<h1>Account Activated!</h1>
	<p class="single-center-content">
		Your TWHL account has been activated.
		Now you can post in forums, submit new maps, and even add new tutorials!
		Just use the form below to log in to the site.
	</p>
	<form class="login" action="lognow.php" method="post">
		<p class="single-center-content-center">
		Username: <input type="text" name="name" size="16" value="<?=$user_name?>" /><br />
		Password: <input type="password" name="pass" size="16" /><br />
		<input type="submit" value="Login" />
		</p>
	</form>
</div>

<?
	} else {
?>

<div class="single-center">
	<h1>Incorrect Information</h1>
	<p class="single-center-content">
		Sorry, your account could not be validated. Please check that you copied the validation link correctly, and try again.
		Alternatively, if you still cannot activate your account, use the <a href="contact.php">Contact Us</a> page to contact the site admin.
	</p>
</div>

<?
	}
	include 'bottom.php';
?>