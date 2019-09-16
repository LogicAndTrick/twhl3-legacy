<?
	include 'middle.php';
	
	if (!(isset($usr) && ($usr != ""))) fail("You must be logged in to view this page.","index.php",true);
	$peditq = mysql_query("SELECT * FROM users WHERE userID = '$usr'");
	if (mysql_num_rows($peditq) == 0) fail("You don't exist.","index.php",true);
	$getjourn = mysql_real_escape_string($_GET['id']);
	if (!isset($getjourn) || !is_numeric($getjourn) || $getjourn == "") fail("No Journal Specified","user.php?control&journal",true);
	
	if (isset($lvl) && ($lvl >= 35)) $jeditq = mysql_query("SELECT * FROM journals WHERE journalID = '$getjourn'");
	else $jeditq = mysql_query("SELECT * FROM journals WHERE journalID = '$getjourn' AND ownerID = '$usr'");
	
	if (mysql_num_rows($jeditq) == 0) fail("Journal not found.","user.php?control&journal");
	
	$getidr = mysql_fetch_array($jeditq);
	$user_redir = $getidr['ownerID'];
	
	$jcont = htmlfilter($_POST['journtext'],true);
	if (!isset($jcont) || $jcont == "") fail("You cannot submit an empty journal.","user.php?control&journal",true);
	
	mysql_query("UPDATE journals SET journaltext = '$jcont' WHERE journalID = '$getjourn' LIMIT 1");
	
	header("Location: journals.php?id=$getjourn");
?>