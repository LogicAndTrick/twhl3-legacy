<?php

include 'functions.php';
include 'logins.php';

if (!(isset($usr) && ($usr != ""))) fail("You must be logged in to do this.","index.php",true);
if (!(isset($_POST['details']) && $_POST['details']!="")) fail("Please fill in the description.","index.php",true);
if (!(isset($_POST['title']) && $_POST['title']!="")) fail("Please fill in the name of the tutorial.","index.php",true);

	$propuser=mysql_real_escape_string($_SESSION['usr']);
	$diff=mysql_real_escape_string((int)$_POST['difficulty']);
	$eng=mysql_real_escape_string((int)$_POST['engine']);
	$thenow=gmt(U);
	$name=htmlfilter($_POST['title']);
	$keys=htmlfilter($_POST['keywords']);
	$dets=htmlfilter($_POST['details']);
	mysql_query("INSERT INTO tutorialproposals (propuser,propdifficulty,propgame,propdate,propname,propkeywords,propdetails,accepted,rejectreason) VALUES ('$propuser','$diff','$eng','$thenow','$name','$keys','$dets','0','')");
	$notes=htmlfilter($_POST['notes']);
	if ($notes != "")
	{
		$row=mysql_fetch_array(mysql_query("SELECT propID FROM tutorialproposals ORDER BY propID DESC LIMIT 1"));
		$newid=$row['propID'];
		mysql_query("INSERT INTO tutorialpropcoms (proposalID,comuser,comdate,accept,comtext) VALUES ('$newid','$propuser','$thenow','0','$notes')");
	}
	header("Location: tutorial.php?thanks");


?>