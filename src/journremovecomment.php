<?
	include 'middle.php';
	if (!(isset($usr) && ($usr != ""))) fail("You must be logged in to view this page.","index.php",true);
	if (!(isset($lvl) && ($lvl >= 35))) fail("You are not allowed to do this.","journals.php?id=$cjourn",true);
	$peditq = mysql_query("SELECT * FROM users WHERE userID = '$usr'");
	if (mysql_num_rows($peditq) == 0) fail("You don't exist.","index.php",true);
	$getcomm = mysql_real_escape_string($_GET['id']);
	if (!isset($getcomm) || !is_numeric($getcomm) || $getcomm == "") fail("No Comment Specified","journals.php",true);
	$ceditq = mysql_query("SELECT * FROM journalcomments LEFT JOIN users ON commuser = userID WHERE commID = '$getcomm'");
	if (mysql_num_rows($ceditq) == 0) fail("Comment not found.","journals.php",true);
	$cedr = mysql_fetch_array($ceditq);
	$cjourn = $cedr['commjournal'];
		
	mysql_query("DELETE FROM journalcomments WHERE commID = '$getcomm'");
	mysql_query("UPDATE journals SET stat_coms = stat_coms-1 WHERE journalID = '$cjourn'");
	header("Location: journals.php?id=".$cjourn);
?>