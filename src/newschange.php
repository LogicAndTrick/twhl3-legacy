<?php
	include 'middle.php';
	
	if (!(isset($lvl) && ($lvl >= 35))) fail("You aren't logged in, or you don't have permission to do this.","index.php",true);
	if (!(isset($_GET['id']))) fail("No news post specified.","index.php",true);
	if (!(isset($_POST['title']) && ($_POST['title']!="") && isset($_POST['newstext']) && ($_POST['newstext']!=""))) fail("The title and/or the content of the news is empty.","news.php?post",true);
	
	$edit=mysql_real_escape_string($_GET['id']);
	
	$ntitle = htmlfilter($_POST['title'],true);
	$npost = htmlfilter($_POST['newstext'],true);
	
	if ($_POST['newdate']) mysql_query("UPDATE news SET date = '".gmt("U")."' WHERE newsID='$edit'");
	if ($_POST['userid']!="" && is_numeric($_POST['userid'])) mysql_query("UPDATE news SET newscaster = '".mysql_real_escape_string($_POST['userid'])."' WHERE newsID='$edit'");
	
	mysql_query("UPDATE news SET title='$ntitle', newsart='$npost' WHERE newsID='$edit'");
	mysql_close($dbh);
	
	header("Location: index.php");
?>