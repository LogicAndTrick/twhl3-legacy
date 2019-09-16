<?php	

	include 'middle.php';

	$tutid=mysql_real_escape_string($_GET['id']);
	$result = mysql_query("SELECT * FROM tutorials WHERE tutorialID = '$tutid'");
	$row = mysql_fetch_array($result);
	$authorid = $row['authorid'];
	$waiting = $row['waiting'];
	if (!((isset($_SESSION['usr']) && ($authorid == $_SESSION['usr'])) || (isset($_SESSION['lvl']) && ($_SESSION['lvl'] >= 25)))) fail("You aren't logged in, or you don't have permission to do this.","index.php",true);

		if ($waiting == 1)
			$result1 = mysql_query("SELECT * FROM tutorialpages WHERE tutorialid = '$tutid'");
		else
			$result1 = mysql_query("SELECT * FROM tutorialpages WHERE tutorialid = '$tutid' AND isactive > 0");
		
		$count = mysql_num_rows($result1);
		$new = $count;
		if ($count < 5)
		{
			$new = $count + 1;
			$thenow = gmt("U");
			
			if ($waiting == 1)
				$act = 0;
			else
				$act = 2;
			
			mysql_query("INSERT INTO tutorialpages (tutorialid,page,content,pagedate,isactive) VALUES ('$tutid','$new','','$thenow','$act')");
			mysql_close($dbh);
			header("Location: tutorial.php?edit=$tutid&page=$new");
		}
		else echo "can't create > 5 pages";
?>