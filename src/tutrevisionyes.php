<?php

include 'middle.php';

	$gettut=mysql_real_escape_string($_GET['id']);
	$result = mysql_query("SELECT * FROM tutorials WHERE tutorialID = '$gettut'");
	
	if (mysql_num_rows($result) == 0) fail("Tutorial not found.","tutorial.php",true);
	if (!(isset($lvl) && ($lvl >= 25))) fail("You aren't logged in, or you don't have permission to do this.","index.php",true);
	
	$row = mysql_fetch_array($result);
	$authorid = $row['authorid'];
	$tutname = $row['name'];
	
	$newq = mysql_query("SELECT * FROM tutorialpages WHERE tutorialid = '$gettut' AND isactive = '-1' LIMIT 1");
	
	if (mysql_num_rows($newq) == 0) fail("Revision not found.","tutorial.php",true);
	
		$newr = mysql_fetch_array($newq);
		$newpage = $newr['page'];
		mysql_query("UPDATE tutorialpages SET isactive = '0' WHERE tutorialid = '$gettut' AND page = '$newpage' AND isactive = '1' LIMIT 1");
		mysql_query("UPDATE tutorialpages SET isactive = '1' WHERE tutorialid = '$gettut' AND page = '$newpage' AND isactive = '-1' LIMIT 1");
		
		$thenow = gmt("U");
		
		mysql_query("INSERT INTO alertuser (alertuser,alerter,alerttype,alertdate,alertlink,alertcontent,isnew) VALUES ('$authorid','$usr','11','$thenow','$gettut','An edit to your tutorial, $tutname, has been approved.','1')");
		
		header("Location: tutorial.php?id=$gettut");
?>