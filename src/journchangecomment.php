<?
	include 'middle.php';
	if (!(isset($usr) && ($usr != ""))) fail("You must be logged in to view this page.","index.php",true);
	$peditq = mysql_query("SELECT * FROM users WHERE userID = '$usr'");
	if (mysql_num_rows($peditq) == 0) fail("You don't exist.","index.php",true);
	$getcomm = mysql_real_escape_string($_GET['id']);
	if (!isset($getcomm) || !is_numeric($getcomm) || $getcomm == "") fail("No Comment Specified","journals.php",true);
	
	if (isset($lvl) && ($lvl >= 35)) $ceditq = mysql_query("SELECT * FROM journalcomments LEFT JOIN users ON commuser = userID WHERE commID = '$getcomm'");
	else $ceditq = mysql_query("SELECT * FROM journalcomments LEFT JOIN users ON commuser = userID WHERE commID = '$getcomm' AND commuser = '$usr'");
	
	if (mysql_num_rows($ceditq) == 0) fail("Comment not found.","journals.php",true);
	
	$mod = false;
	if ($_SESSION['lvl'] >= 35) $mod = true;
	
	$cedr = mysql_fetch_array($ceditq);
	$cjourn = $cedr['commjournal'];
	
	$lastposter = -1;
	$lastpostid = -1;
	$comeditq = mysql_query("SELECT * FROM journalcomments WHERE commjournal = '$cjourn' ORDER BY commdate DESC LIMIT 1");
	if (mysql_num_rows($comeditq) > 0)
	{
		$comeditr = mysql_fetch_array($comeditq);
		$lastposter = $comeditr['commuser'];
		$lastpostid = $comeditr['commID'];
	}
	
	if (!($mod || (($lastposter == $usr) && ($lastpostid == $getcomm)))) fail("You are not allowed to do this.","journals.php?id=$cjourn",true);
	
	$text=htmlfilter($_POST['comment'],true);
		
	mysql_query("UPDATE journalcomments SET commtext = '$text' WHERE commID = '$getcomm'");
	header("Location: journals.php?id=".$cjourn);
?>