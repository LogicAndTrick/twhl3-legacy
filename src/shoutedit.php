<?
	//login and other user stuffs
	include 'functions.php';
	include 'logins.php';
	
	if (isset($lvl) && ($lvl >= 35) && isset($_POST['shout']) && trim($_POST['shout'])!="")
	{
		$updateshout=htmlfilter(urldecode($_POST['shout']));
		$updateid = mysql_real_escape_string($_GET['id']);
		mysql_query("UPDATE shouts SET shout='$updateshout' WHERE shoutID = '$updateid'");
	}
?>