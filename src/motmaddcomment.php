<?php	

	include 'middle.php';

	if (!(isset($usr) && ($usr != ""))) fail("You must be logged in to do this.","index.php",true);
	if (!(isset($_GET['id']) && ($_GET['id'] != "") && (is_numeric($_GET['id'])))) fail("No MOTM specified.","motm.php",true);
	$getmotm = mysql_real_escape_string($_GET['id']);
	$motmq = mysql_query("SELECT * FROM motmreviews WHERE reviewID = '$getmotm'");
	if (mysql_num_rows($motmq) == 0) fail("Review not found.","news.php",true);
	
	$motmr = mysql_fetch_array($motmq);
	$motmid = $motmr['motm'];
	$thenow=gmt("U");
	$text=htmlfilter($_POST['comment'],true);
	
	if (!(isset($_POST['comment']) && (trim($_POST['comment']) != ""))) fail("Cannot submit empty comments.","motm.php?id=$motmid",true);
	
	$focus = "main";
	if (isset($_GET['focus']) && is_numeric($_GET['focus']) && $_GET['focus'] > 0) $focus = mysql_real_escape_string($_GET['focus']);
	
	mysql_query("INSERT INTO motmcomments (commmotm,commuser,commdate,commtext) VALUES ('$getmotm','$usr','$thenow','$text')");
	mysql_query("UPDATE users SET stat_motmcoms = stat_motmcoms+1 WHERE userID = '$usr'");
	mysql_query("UPDATE motmreviews SET stat_coms = stat_coms+1 WHERE reviewID = '$getmotm'");
	
	header("Location: motm.php?id=$motmid&focus=$focus");
?>