<?php

include 'middle.php';

$thd=mysql_real_escape_string($_GET['id']);

if (isset($_SESSION['lvl']) &&
isset($_POST['posttxt']) && (trim($_POST['posttxt'])!="") &&
isset($thd) && (trim($thd)!="") && is_numeric($thd))
{

	$posttext=htmlfilter($_POST['posttxt'],true);
	$postdate=gmt(U);
	
	if (mysql_num_rows(mysql_query("SELECT * FROM threads WHERE threadID = $thd")) == 0) fail("Thread not found.","forums.php?thread=$thd&amp;page=last",true);
	if (mysql_num_rows(mysql_query("SELECT * FROM threads WHERE threadID = $thd AND prop_open = '0'")) > 0) fail("This thread is closed. You cannot post in it.","forums.php?thread=$thd&amp;page=last",true);
	
	$gettingcat = mysql_fetch_array(mysql_query("SELECT forumcat FROM threads WHERE threadID = $thd LIMIT 1"));
	$cat = $gettingcat['forumcat'];

	mysql_query("INSERT INTO posts (forumcat,threadid,posterid,postdate,postedit,posttext)
	VALUES
	('$cat','$thd','$usr','$postdate','0','$posttext')");
	
	if (($_SESSION['lvl'] >= 35) && ($_POST['close']))
	{
		mysql_query("UPDATE threads SET prop_open = '0' WHERE threadID = '$thd'");
	}

	$getlast = mysql_fetch_array(mysql_query("SELECT postID FROM posts ORDER BY postID DESC LIMIT 1"));
	$getlast2 = $getlast['postID'];

	mysql_query("UPDATE users SET stat_posts = stat_posts+1 WHERE userID = '$usr'");
	mysql_query("UPDATE threads SET stat_lastpostid = '$getlast2', stat_replies = stat_replies+1 WHERE threadID = '$thd'");
	mysql_query("UPDATE forumcats SET stat_lastpostid = '$getlast2', stat_posts = stat_posts+1 WHERE forumID = '$cat'");
	mysql_query("UPDATE threadtracking SET isnew = isnew+1 WHERE trackthread = '$thd' AND trackuser != '$usr'");
	
	mysql_close($dbh);
	header("Location: forums.php?thread=$thd&page=last");

}
else
{
	include 'header.php';
	include 'sidebar.php';
	if (!isset($thd) || (trim($thd)=="") || !is_numeric($thd))
	{
		$problem = "There is no thread specified.";
		$back = "forums.php";
	}
	elseif (!isset($_POST['posttxt']) || (trim($_POST['posttxt'])==""))
	{
		$problem = "Your post content appears to be empty";
		$back = "forums.php?thread=".$thd;
	}
	elseif (!isset($_SESSION['lvl']) || ($_SESSION['lvl']==""))
	{
		$problem = "You don't seem to be logged in.";
		$back = "forums.php?thread=".$thd;
	}
	include 'failure.php';
	include 'bottom.php';
}

?>