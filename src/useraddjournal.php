<?
	include 'middle.php';
	
	if (!(isset($usr) && ($usr != ""))) fail("You must be logged in to view this page.","index.php",true);
	$peditq = mysql_query("SELECT * FROM users WHERE userID = '$usr'");
	if (mysql_num_rows($peditq) == 0) fail("You don't exist.","index.php",true);
	
	$jcont = htmlfilter($_POST['journtext'],true);
	if (!isset($jcont) || $jcont == "") fail("You cannot submit an empty journal.","user.php?control&journal",true);
	
	$thenow = gmt("U");
	
	mysql_query("INSERT INTO journals (ownerID,journaldate,journaltext) VALUES ('$usr','$thenow','$jcont')");
	
	header("Location: user.php?id=$usr&journals");
?>