<?php	

	include 'middle.php';

	$pageid=mysql_real_escape_string($_GET['id']);
	$pagq = mysql_query("SELECT * FROM tutorialpages WHERE pageID = '$pageid'");
	if (mysql_num_rows($pagq) > 0)
	{
		$pagr = mysql_fetch_array($pagq);
		$tutid = $pagr['tutorialid'];
		$pageno = $pagr['page'];
		$act = $pagr['isactive'];
		$tutq = mysql_query("SELECT * FROM tutorials WHERE tutorialID = '$tutid'");
		if (mysql_num_rows($tutq) > 0)
		{
			$tutr= mysql_fetch_array($tutq);
			$tutuser = $tutr['authorid'];
			$waiting = $tutr['waiting'];
			$thenow = gmt("U");
			
			if ($waiting == 1)
				$lasq = mysql_query("SELECT * FROM tutorialpages WHERE tutorialid = '$tutid' ORDER BY page DESC");
			else
				$lasq = mysql_query("SELECT * FROM tutorialpages WHERE tutorialid = '$tutid' AND isactive > 0 ORDER BY page DESC");
				
			if ((mysql_num_rows($lasq) > 0) && (isset($_SESSION['usr']) && ($authorid == $_SESSION['usr'])) || (isset($_SESSION['lvl']) && ($_SESSION['lvl'] >= 25)))
			{
				$lasr = mysql_fetch_array($lasq);
				$lastpage = $lasr['page'];
				if ($pageno == $lastpage)
				{
					if ($waiting == 1 || $act == 2)
					{
						mysql_query("DELETE FROM tutorialpages WHERE pageID = '$pageid'");
						mysql_close($dbh);
						header("Location: tutorial.php?edit=$tutid");
					}
					else
					{
						mysql_query("UPDATE tutorialpages SET deletecandidate = '1' WHERE pageID = '$pageid'");
						mysql_query("INSERT INTO alertadmin (alertuser,alerttype,alertdate,alertlink,alertcontent,isnew) VALUES ('$usr','13','$thenow','$pageid','$uid has requested a page of their tutorial, $tutname, be deleted.','1')");
						mysql_close($dbh);
						header("Location: tutorial.php?edit=$tutid");
					}
				}
				else fail("You can only delete the last page of your tutorial.","tutorial.php?edit=$tutid",true);
			}
			else fail("You aren't logged in, or you don't have permission to do this.","tutorial.php",true);
		}
		else fail("Tutorial not found.","tutorial.php",true);
	}
	else fail("Page not found.","tutorial.php",true);
?>