<?
	include 'middle.php';
	
	$getpm = mysql_real_escape_string($_GET['id']);
	
	$pmq = mysql_query("SELECT * FROM pminbox WHERE pmID = '$getpm' AND pmto = '$usr'");
	
	if (!(isset($usr) && ($usr != ""))) fail("You must be logged in to do this.","index.php",true);
	if (mysql_num_rows($pmq) == 0) fail("You cannot archive this message.","privmsg.php",true);
	
	mysql_query("DELETE FROM pminbox WHERE pmID = '$getpm'");
	header("Location: privmsg.php");
?>