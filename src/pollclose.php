<?
	include 'middle.php';
	$getpoll = mysql_real_escape_string($_GET['id']);
	if (!(isset($lvl) && ($lvl >= 40))) fail("You aren't logged in, or you don't have permission to do this.","index.php",true);
	if (!(isset($getpoll))) fail("No Poll specified.","polledit.php",true);
	mysql_query("UPDATE polls SET isactive = 0 WHERE pollID = '$getpoll'");
	header("Location: polledit.php");
?>