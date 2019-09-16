<?

	if (!(isset($usr) && ($usr != ""))) fail("You must be logged in to view this page.","index.php");
	$peditq = mysql_query("SELECT * FROM users WHERE userID = '$usr'");
	if (mysql_num_rows($peditq) == 0) fail("You don't exist.","index.php");
	$getcomm = mysql_real_escape_string($_GET['comment']);
	if (!isset($getcomm) || !is_numeric($getcomm) || $getcomm == "") fail("No Comment Specified","news.php");
	
	if (isset($lvl) && ($lvl >= 35)) $ceditq = mysql_query("SELECT * FROM newscomments LEFT JOIN users ON commuser = userID WHERE commID = '$getcomm'");
	else $ceditq = mysql_query("SELECT * FROM newscomments LEFT JOIN users ON commuser = userID WHERE commID = '$getcomm' AND commuser = '$usr'");
	
	if (mysql_num_rows($ceditq) == 0) fail("Comment not found.","news.php");
	
	$mod = false;
	if ($_SESSION['lvl'] >= 35) $mod = true;
	
	$cedr = mysql_fetch_array($ceditq);
	$cnews = $cedr['commnews'];
	
	$lastposter = -1;
	$lastpostid = -1;
	$comeditq = mysql_query("SELECT * FROM newscomments WHERE commnews = '$cnews' ORDER BY commdate DESC LIMIT 1");
	if (mysql_num_rows($comeditq) > 0)
	{
		$comeditr = mysql_fetch_array($comeditq);
		$lastposter = $comeditr['commuser'];
		$lastpostid = $comeditr['commID'];
	}
	
	if (!($mod || (($lastposter == $usr) && ($lastpostid == $getcomm)))) fail("You are not allowed to do this.","news.php?id=$cnews");
	
	$ctext = $cedr['commtext'];
	$cuser = $cedr['commuser'];
	$cusername = $cedr['uid'];
	
	if (isset($_GET['delete']) && $mod)
		include 'newsdeletecomment.php';
	else
		include 'newseditcomment.php';
?>