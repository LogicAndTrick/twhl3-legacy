<?

	if (!(isset($usr) && ($usr != ""))) fail("You must be logged in to view this page.","index.php");
	$peditq = mysql_query("SELECT * FROM users WHERE userID = '$usr'");
	if (mysql_num_rows($peditq) == 0) fail("You don't exist.","index.php");
	$getjourn = mysql_real_escape_string($_GET['journal']);
	if (!isset($getjourn) || !is_numeric($getjourn) || $getjourn == "") fail("No Journal Specified","user.php?control&journal");
	
	if (isset($lvl) && ($lvl >= 35)) $jeditq = mysql_query("SELECT * FROM journals LEFT JOIN users ON ownerID = userID WHERE journalID = '$getjourn'");
	else $jeditq = mysql_query("SELECT * FROM journals LEFT JOIN users ON ownerID = userID WHERE journalID = '$getjourn' AND ownerID = '$usr'");
	
	if (mysql_num_rows($jeditq) == 0) fail("Journal not found.","user.php?control&journal");
	
	$jedr = mysql_fetch_array($jeditq);
	$jtext = $jedr['journaltext'];
	$juser = $jedr['ownerID'];
	$jusername = $jedr['uid'];
	
	if (isset($_GET['delete']))
		include 'journdelete.php';
	else
		include 'journedit.php';
?>