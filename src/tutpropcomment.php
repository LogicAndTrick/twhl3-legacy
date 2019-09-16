<?php	

include 'functions.php';
include 'logins.php';

if (!(isset($lvl) && ($lvl >= 25))) fail("You aren't logged in, or you don't have permission to do this.","index.php",true);

	if (isset($_GET['comment']) and $_GET['comment']!="")
	{
		$prop=$_GET['comment'];
		$user=$_SESSION['usr'];
		$thenow=gmt(U);
		$accept=(int)$_POST['accept'];
		$text=htmlfilter($_POST['comment'],true);
		mysql_query("INSERT INTO tutorialpropcoms (proposalID,comuser,comdate,accept,comtext) VALUES ('$prop','$user','$thenow','$accept','$text')");
		header("Location: tutorial.php?viewprop=$prop");
	}
	else fail("Proposal not found.","tutorial.php");
?>