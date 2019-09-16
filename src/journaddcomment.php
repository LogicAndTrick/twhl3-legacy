<?php	

	include 'middle.php';

	if (!(isset($usr) && ($usr != ""))) fail("You must be logged in to do this.","index.php",true);
	if (!(isset($_GET['id']) && ($_GET['id'] != "") && (is_numeric($_GET['id'])))) fail("No journal specified.","journals.php",true);
	$getjourn = mysql_real_escape_string($_GET['id']);
	$journq = mysql_query("SELECT * FROM journals WHERE journalID = '$getjourn'");
	if (mysql_num_rows($journq) == 0) fail("Journal not found.","journals.php",true);
	if (!(isset($_POST['comment']) && (trim($_POST['comment']) != ""))) fail("Cannot submit empty comments.","journals.php?id=$getjourn",true);
	
	$journr = mysql_fetch_array($journq);
	$ownerid = $journr['ownerID'];
	$thenow=gmt("U");
	$text=htmlfilter($_POST['comment'],true);
	
	mysql_query("INSERT INTO journalcomments (commjournal,commuser,commdate,commtext) VALUES ('$getjourn','$usr','$thenow','$text')");
	mysql_query("UPDATE users SET stat_jcoms = stat_jcoms+1 WHERE userID = '$usr'");
	mysql_query("UPDATE journals SET stat_coms = stat_coms+1 WHERE journalID = '$getjourn'");
	
	header("Location: journals.php?id=$getjourn");
?>