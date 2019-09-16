<?
	include 'middle.php';
	if (!(isset($usr) && ($usr != ""))) fail("You must be logged in to view this page.","index.php",true);
	if (!(isset($lvl) && ($lvl >= 35))) fail("You are not allowed to do this.","news.php",true);
	$peditq = mysql_query("SELECT * FROM users WHERE userID = '$usr'");
	if (mysql_num_rows($peditq) == 0) fail("You don't exist.","index.php",true);
	$getcomm = mysql_real_escape_string($_GET['id']);
	if (!isset($getcomm) || !is_numeric($getcomm) || $getcomm == "") fail("No Comment Specified","motm.php",true);
	$ceditq = mysql_query("SELECT * FROM motmcomments LEFT JOIN users ON commuser = userID WHERE commID = '$getcomm'");
	if (mysql_num_rows($ceditq) == 0) fail("Comment not found.","motm.php",true);
	$cedr = mysql_fetch_array($ceditq);
	$cmotm = $cedr['commmotm'];
	$cmotmq = mysql_query("SELECT * FROM motmreviews WHERE reviewID = '$cmotm'");
	if (mysql_num_rows($cmotmq) == 0) fail("MOTM not found.","motm.php",true);
	$cmotmr = mysql_fetch_array($cmotmq);
	$cmotmid = $cmotmr['motm'];
	
	$focus = "main";
	if (isset($_GET['focus']) && is_numeric($_GET['focus']) && $_GET['focus'] > 0) $focus = mysql_real_escape_string($_GET['focus']);
	
	mysql_query("DELETE FROM motmcomments WHERE commID = '$getcomm'");
	mysql_query("UPDATE motmreviews SET stat_coms = stat_coms-1 WHERE reviewID = '$cmotm'");
	header("Location: motm.php?id=$cmotmid&focus=$focus");
?>