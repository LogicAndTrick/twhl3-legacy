<?php
	include 'middle.php';
	
	$getjudge = mysql_real_escape_string($_GET['id']);
	
	$judgeq = mysql_query("SELECT * FROM compjudges WHERE judgeID = '$getjudge'");
	
	if (!(isset($lvl) && ($lvl >= 40))) fail("You aren't logged in, or you don't have permission to do this.","competitions.php",true);
	if (mysql_num_rows($judgeq) == 0) fail("Judge not found.","competitions.php",true);
	
	$judger = mysql_fetch_array($judgeq);
	$compid = $judger['judgecomp'];
	mysql_query("DELETE FROM compjudges WHERE judgeID = '$getjudge'");
	header("Location: competitions.php?hub=$compid&judges");
?>