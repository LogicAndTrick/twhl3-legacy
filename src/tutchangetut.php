<?php

include 'middle.php';

	$editid=mysql_real_escape_string($_GET['id']);
	$result = mysql_query("SELECT * FROM tutorials WHERE tutorialID = '$editid'");
	$valid = false;
	if (mysql_num_rows($result) > 0)
	{
		$row = mysql_fetch_array($result);
		$authorid = $row['authorid'];
		$valid = true;
	}
	
	if (!((isset($_SESSION['usr']) && ($authorid == $_SESSION['usr'])) || (isset($_SESSION['lvl']) && ($_SESSION['lvl'] >= 25)))) fail("You aren't logged in, or you don't have permission to do this.","index.php",true);
	if (!$valid) fail("Tutorial not found.","tutorial.php",true);
		
		$nname = htmlfilter($_POST['tutname']);
		$ntop = htmlfilter($_POST['tuttopics']);
		$ndiff = htmlfilter($_POST['tutdiff']);
		$nsect = htmlfilter($_POST['tutsect']);
		$ndesc = htmlfilter($_POST['tutdesc']);
		$nexc = htmlfilter($_POST['tutcont']);
		$nexn = htmlfilter($_POST['tutnotes']);
		
		$thenow = gmt("U");
		
		$nfile = true;
		
		$ferror = $_FILES['tutexample']['error'];
		$fname = $_FILES['tutexample']['name'];
		$ftype = $_FILES['tutexample']['type'];
		$fsize = $_FILES['tutexample']['size'];
		$ftmp = $_FILES['tutexample']['tmp_name'];
		if (!$fname || !isset($fname) || $fname == "") $nfile = false;
		if ($ferror > 0) $nfile = false;
		if (!is_uploaded_file($ftmp)) $nfile = false;
		if (!eregi('.zip',substr($fname,-4)) && !eregi('.rar',substr($fname,-4))) $nfile = false;
		if ($fsize > 1048576) $nfile = false;
		
		if ($nfile)
		{
			//$filename = basename($fname);
			$filename = $editid."_".preg_replace('/[^ 0-9a-z._-]/si', '', str_replace(" ","_",$nname)).".".substr($fname,-3);
			move_uploaded_file($ftmp, "tutorialdl/".$filename) or die ('Sorry, server is being stupid. Please retry your upload.');
            $filename = mysql_real_escape_string($filename);
			mysql_query("UPDATE tutorials SET example = '$filename', examplesize = '$fsize' WHERE tutorialID = '$editid'");
		}
		mysql_query("UPDATE tutorials SET name = '$nname', catID = '$nsect', description = '$ndesc', topics = '$ntop', difficulty = '$ndiff', examplecont = '$nexc', examplenotes = '$nexn', editdate = '$thenow' WHERE tutorialID = '$editid'") or die(mysql_error());
		
		if ($_SESSION['lvl'] < 25) mysql_query("INSERT INTO alertadmin (alertuser,alerttype,alertdate,alertlink,alertcontent,isnew) VALUES ('$usr','10','$thenow','$editid','$uid has changed the base info on his tutorial, $nname.','1')");
		
		header("Location: tutorial.php?id=$editid");
?>