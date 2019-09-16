<?php

include 'middle.php';

	$gettut=mysql_real_escape_string($_GET['id']);
	
	$result = mysql_query("SELECT * FROM tutorials WHERE tutorialID = '$gettut'");
	$valid = false;
	if (mysql_num_rows($result) > 0)
	{
		$row = mysql_fetch_array($result);
		$authorid = $row['authorid'];
		$tutname = $row['name'];
		$valid = true;
	}
	if (!(isset($lvl) && ($lvl >= 25))) fail("You aren't logged in, or you don't have permission to do this.","index.php",true);
	
	if (!$valid) fail("Tutorial not found.","index.php",true);
		
		$thenow = gmt("U");
		
		mysql_query("UPDATE tutorials SET waiting = '0', date = '$thenow' WHERE tutorialID = '$gettut'");
		mysql_query("UPDATE tutorialpages SET isactive = '1' WHERE tutorialid = '$gettut' AND isactive < 0");
		
		$thenow = gmt("U");
		
		mysql_query("INSERT INTO alertuser (alertuser,alerter,alerttype,alertdate,alertlink,alertcontent,isnew) VALUES ('$authorid','$usr','4','$thenow','$gettut','Your tutorial, $tutname, has gone live!','1')");
		header("Location: tutorial.php?id=$gettut");
?>