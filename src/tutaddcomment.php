<?php	

	include 'functions.php';
	include 'logins.php';

	if (!(isset($usr) && ($usr != ""))) fail("You aren't logged in, or you don't have permission to do this.","index.php",true);
	if (!(isset($_GET['id']) && ($_GET['id'] != ""))) fail("No tutorial specified.","index.php",true);

	$tutid=mysql_real_escape_string($_GET['id']);
	$user=mysql_real_escape_string($_SESSION['usr']);
	$thenow=gmt(U);
	$rating=0;
	if (isset($_POST['rating']) && $_POST['rating']!="")
		$rating=mysql_real_escape_string($_POST['rating']);
	$text=htmlfilter($_POST['comment'],true);
	
	if (!isset($text) || $text == "") fail("You cannot submit blank comments!","tutorial.php?id=$tutid",true);
	
	mysql_query("INSERT INTO tutorialcomments (commtut,commuser,commtime,commtext,rating) VALUES ('$tutid','$user','$thenow','$text','$rating')");
	
	if ($rating > 0)
	{
		mysql_query("UPDATE tutorials SET rating = rating + $rating, ratings = ratings + 1 WHERE tutorialID = '$tutid' LIMIT 1");
	}
	
	header("Location: tutorial.php?id=$tutid");
?>