<?php

include 'middle.php';

	$gettut=mysql_real_escape_string($_GET['id']);
	$getpage=mysql_real_escape_string($_GET['page']);
	$result = mysql_query("SELECT * FROM tutorials WHERE tutorialID = '$gettut'");
	$valid = false;
	if (mysql_num_rows($result) > 0)
	{
		$row = mysql_fetch_array($result);
		$authorid = $row['authorid'];
		$waiting = $row['waiting'];
		$tutname = $row['name'];
		
		if ($waiting == 1)
			$pagq = mysql_query("SELECT * FROM tutorialpages WHERE tutorialid = '$gettut' AND page = '$getpage' ORDER BY pageID DESC LIMIT 1");
		else
			$pagq = mysql_query("SELECT * FROM tutorialpages WHERE tutorialid = '$gettut' AND page = '$getpage' AND isactive > 0 ORDER BY pageID DESC LIMIT 1");
		
		if (mysql_num_rows($pagq) > 0)
		{
			$pagr = mysql_fetch_array($pagq);
			$pagid = $pagr['pageID'];
			$valid = true;
		}
	}
	
	if (!((isset($_SESSION['usr']) && ($authorid == $_SESSION['usr'])) || (isset($_SESSION['lvl']) && ($_SESSION['lvl'] >= 25)))) fail("You aren't logged in, or you don't have permission to do this.","index.php",true);
	if (!$valid) fail("Tutorial not found.","tutorial.php",true);

		$ncont = htmlfilter($_POST['pagecontent'],true);
		$thenow = gmt("U");

		if (isset($_POST['adminsaveandquit']) && ($_SESSION['lvl'] >= 25))
		{
			$thenow = gmt("U");
			mysql_query("UPDATE tutorialpages SET content = '$ncont' WHERE pageID = '$pagid'");
			mysql_query("UPDATE tutorials SET waiting = '0', date = '$thenow' WHERE tutorialID = '$gettut'");
			mysql_query("UPDATE tutorialpages SET isactive = '1' WHERE tutorialid = '$gettut'");
			header("Location: tutorial.php?id=$gettut");
		}
		elseif (isset($_POST['save']) || ($_SESSION['lvl'] >= 25))
		{
			mysql_query("UPDATE tutorialpages SET content = '$ncont' WHERE pageID = '$pagid'");
			header("Location: tutorial.php?edit=$gettut&page=$getpage");
		}
		elseif (isset($_POST['saveandquit']))
		{
			mysql_query("UPDATE tutorialpages SET content = '$ncont' WHERE pageID = '$pagid'");
			mysql_query("UPDATE tutorialpages SET isactive = '-1' WHERE tutorialid = '$gettut'");
			mysql_query("INSERT INTO alertadmin (alertuser,alerttype,alertdate,alertlink,alertcontent,isnew) VALUES ('$usr','7','$thenow','$gettut','$uid has submitted their tutorial, $tutname, for review.','1')");
			header("Location: tutorial.php?mytuts");
		}
		elseif (isset($_POST['newpagequit']))
		{
			mysql_query("UPDATE tutorialpages SET content = '$ncont' WHERE pageID = '$pagid'");
			mysql_query("UPDATE tutorialpages SET isactive = '-1' WHERE tutorialid = '$gettut' AND pageID = '$pagid'");
			mysql_query("INSERT INTO alertadmin (alertuser,alerttype,alertdate,alertlink,alertcontent,isnew) VALUES ('$usr','14','$thenow','$pagid','$uid has submitted a new page to their tutorial, $tutname, for review.','1')");
			header("Location: tutorial.php?mytuts");
		}
		elseif (isset($_POST['revise']))
		{
			$revr = mysql_fetch_array(mysql_query("SELECT * FROM tutorialpages WHERE tutorialid = '$gettut' AND page = '$getpage' ORDER BY revision DESC LIMIT 1"));
			$rev = $revr['revision'] + 1;
			mysql_query("UPDATE tutorialpages SET isactive = '0' WHERE tutorialid = '$gettut' AND page = '$getpage' AND isactive = '2'");
			mysql_query("INSERT INTO tutorialpages (tutorialid, page, revision, content, pagedate, isactive) VALUES ('$gettut', '$getpage', '$rev', '$ncont', '$thenow', '-1')");
			mysql_query("INSERT INTO alertadmin (alertuser,alerttype,alertdate,alertlink,alertcontent,isnew) VALUES ('$usr','8','$thenow','$gettut','$uid has submitted a revision to their tutorial, $tutname, for checking.','1')");
			header("Location: tutorial.php?mytuts");
		}
		else
		{
			header("Location: tutorial.php?edit=$gettut&page=$getpage");
		}


?>