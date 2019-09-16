<?php

include 'middle.php';

$cat=mysql_real_escape_string($_GET['id']);

if (isset($cat) && (trim($cat)!="") &&
isset($_POST['threadtxt']) && (trim($_POST['threadtxt'])!="") &&
isset($_POST['threadtitle']) && (trim($_POST['threadtitle'])!="") &&
isset($_SESSION['lvl']) && $_SESSION['lvl']!="")
{

	$threadtit=htmlfilter($_POST['threadtitle']);
	$now=gmt(U);
	$text=htmlfilter($_POST['threadtxt'],true);
/*
	$check1 = mysql_fetch_array(mysql_query("SELECT threadID FROM threads ORDER BY threadID DESC LIMIT 1"));
	$check2 = $check1['threadID'];
	$check3 = $check2 + 1;
*/
	mysql_query("INSERT INTO posts (forumcat,threadid,posterid,postdate,postedit,posttext)
	VALUES
	('$cat','','$usr','$now','0','$text')");

	$getlast = mysql_query("SELECT postID FROM posts ORDER BY postID DESC LIMIT 1");
	$getlast1 = mysql_fetch_array($getlast);
	$getlast2 = $getlast1['postID'];
	
	$sticky = "0";
	$open = "1";
	if ($_SESSION['lvl'] >= 35)
	{
		if ($_POST['announce'])
		{
			$sticky = "1";
			$open = "0";
		}
		elseif ($_POST['sticky'])
		{
			$sticky = "1";
			$open = "1";
		}
	}

	mysql_query("INSERT INTO threads
	(forumcat,name,ownerid,posttime,stat_views,stat_replies,stat_lastpostid,prop_open,prop_sticky)
	VALUES
	('$cat','$threadtit','$usr','$now','0','0','$getlast2','$open','$sticky')");
	
	$check1 = mysql_fetch_array(mysql_query("SELECT threadID FROM threads ORDER BY threadID DESC LIMIT 1"));
	$check2 = $check1['threadID'];
	mysql_query("UPDATE posts SET threadid='$check2' WHERE postID='$getlast2'");

	mysql_query("UPDATE forumcats
	SET stat_lastpostid = '$getlast2', stat_topics = stat_topics+1, stat_posts = stat_posts+1
	WHERE forumID = '$cat'");
	
	mysql_query("UPDATE users SET stat_posts = stat_posts+1 WHERE userID = '$usr'");
	
	mysql_close($dbh);
	header("Location: forums.php?thread=$check2");

}
else
{
	include 'header.php';
	include 'sidebar.php';
	if (!isset($cat) || (trim($cat)==""))
	{
		$problem = "There is no forum specified.";
		$back = "forums.php";
	}
	elseif (!isset($_POST['threadtxt']) || !isset($_POST['threadtitle']) || (trim($_POST['threadtitle'])=="") || (trim($_POST['threadtxt'])==""))
	{
		$problem = "Your thread title or content appears to be empty";
		$back = "forums.php?id=".$cat;
	}
	elseif (!isset($_SESSION['lvl']) || ($_SESSION['lvl']==""))
	{
		$problem = "You don't seem to be logged in.";
		$back = "forums.php?id=".$cat;
	}
	include 'failure.php';
	include 'bottom.php';
}

?>