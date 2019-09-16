<?
	include 'middle.php';
	if (!(isset($usr) && ($usr != ""))) fail("You must be logged in to view this page.","index.php",true);
	$peditq = mysql_query("SELECT * FROM users WHERE userID = '$usr'");
	if (mysql_num_rows($peditq) == 0) fail("You don't exist.","index.php",true);
	$getcomm = mysql_real_escape_string($_GET['id']);
	if (!isset($getcomm) || !is_numeric($getcomm) || $getcomm == "") fail("No Comment Specified","motm.php",true);
	
	if (isset($lvl) && ($lvl >= 35)) $ceditq = mysql_query("SELECT * FROM motmcomments LEFT JOIN users ON commuser = userID WHERE commID = '$getcomm'");
	else $ceditq = mysql_query("SELECT * FROM motmcomments LEFT JOIN users ON commuser = userID WHERE commID = '$getcomm' AND commuser = '$usr'");
	
	if (mysql_num_rows($ceditq) == 0) fail("Comment not found.","motm.php",true);
	
	$mod = false;
	if ($_SESSION['lvl'] >= 35) $mod = true;
	
	$cedr = mysql_fetch_array($ceditq);
	$cmotm = $cedr['commmotm'];
	
	$lastposter = -1;
	$lastpostid = -1;
	$comeditq = mysql_query("SELECT * FROM motmcomments WHERE commmotm = '$cmotm' ORDER BY commdate DESC LIMIT 1");
	if (mysql_num_rows($comeditq) > 0)
	{
		$comeditr = mysql_fetch_array($comeditq);
		$lastposter = $comeditr['commuser'];
		$lastpostid = $comeditr['commID'];
	}
	
	$cmotmq = mysql_query("SELECT * FROM motmreviews WHERE reviewID = '$cmotm'");
	if (mysql_num_rows($cmotmq) == 0) fail("MOTM not found.","motm.php",true);
	$cmotmr = mysql_fetch_array($cmotmq);
	$cmotmid = $cmotmr['motm'];
	
	if (!($mod || (($lastposter == $usr) && ($lastpostid == $getcomm)))) fail("You are not allowed to do this.","motm.php?id=$cmotmid",true);
	
	$text=htmlfilter($_POST['comment'],true);
	
	$focus = "main";
	if (isset($_GET['focus']) && is_numeric($_GET['focus']) && $_GET['focus'] > 0) $focus = mysql_real_escape_string($_GET['focus']);
		
	mysql_query("UPDATE motmcomments SET commtext = '$text' WHERE commID = '$getcomm'");
	header("Location: motm.php?id=$cmotmid&focus=$focus");
?>