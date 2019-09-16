<?php

include 'middle.php';

	$pageid=mysql_real_escape_string($_GET['id']);
	$pagq = mysql_query("SELECT * FROM tutorialpages LEFT JOIN tutorials ON tutorialpages.tutorialid = tutorials.tutorialID WHERE pageID = '$pageid' AND deletecandidate = 1");
	
	if (!(isset($lvl) && ($lvl >= 25))) fail("You aren't logged in, or you don't have permission to do this.","index.php",true);
	
	if (mysql_num_rows($pagq) == 0) fail("Page not found.","tutorial.php",true);
	
	$pagr = mysql_fetch_array($pagq);
	$author = $pagr['authorid'];
	$tutid = $pagr['tutorialID'];
	$tutname = $pagr['name'];
	mysql_query("UPDATE tutorialpages SET deletecandidate = '0' WHERE pageID = '$pageid'");
	$thenow = gmt("U");
	mysql_query("INSERT INTO alertuser (alertuser,alerter,alerttype,alertdate,alertlink,alertcontent,isnew) VALUES ('$author','$usr','12','$thenow','$tutid','Your request to delete a page in your tutorial, $tutname, has been refused.','1')");

	header("Location: tutorial.php?id=$tutid");
?>