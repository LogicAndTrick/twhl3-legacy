<?
	//login and other user stuffs
	include 'functions.php';
	include 'logins.php';
	
	if (isset($lvl) && ($lvl >= 35) && isset($_GET['id']))
	{
		$updateid = mysql_real_escape_string($_GET['id']);
		mysql_query("DELETE FROM shouts WHERE shoutID = '$updateid'");
	}
?>