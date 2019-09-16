<?php

	include 'middle.php';
	
	if (!(isset($lvl) && ($lvl >= 35))) fail("You aren't logged in, or you don't have permission to do this.","index.php",true);
	if (!(isset($_GET['id']))) fail("No news post specified.","index.php",true);
	
	$delete=mysql_real_escape_string($_GET['id']);
	mysql_query("DELETE FROM news WHERE newsID='$delete'");
	mysql_close($dbh);
	header("Location: index.php");

?>