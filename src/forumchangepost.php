<?php

include 'middle.php';

$id=mysql_real_escape_string($_GET['id']);

$one=mysql_fetch_array(mysql_query("SELECT posts.*,lvl FROM posts LEFT JOIN users ON posterid = userID WHERE postID='$id' LIMIT 1"));
$post_thread = $one['threadid'];
$poster = $one['posterid'];
$post_level = $one['lvl'];
$forumid = $one['forumcat'];
$two=mysql_fetch_array(mysql_query("SELECT * FROM threads WHERE threadID='$post_thread' LIMIT 1"));
$last_post = $two['stat_lastpostid'];
	
if (isset($id) && isset($_SESSION['uid']) && isset($_POST['posttxt']) &&
(trim($_POST['posttxt'])!="") && 
((($_SESSION['lvl']>=35) && (($_SESSION['lvl']>$post_level) || ($poster == $_SESSION['usr']))) ||
(($last_post == $id) && ($poster == $_SESSION['usr']))))
{
	$npost = htmlfilter($_POST['posttxt'],true);
	$thebefore = $one['postdate'];
	$thenow = $one['postdate'];
	$theedit = $one['postedit'];
	$newid = $poster;
	if (isset($theedit) && ($theedit>0) && is_numeric($theedit))
	{
		$thebefore = $one['postedit'];
	}
	if (($last_post == $id) && ($poster == $_SESSION['usr']))
	{
		$thenow = gmt(U);
	}
	if (($_SESSION['lvl'] >= 35) && ($_POST['newid']!="") && (is_numeric($_POST['newid'])))
	{
		$newid=$_POST['newid'];
	}
	mysql_query("UPDATE posts SET posttext='$npost', postdate='$thenow', postedit='$thebefore', posterid='$newid' WHERE postID='$id'");
	
	$newf = mysql_fetch_array(mysql_query("SELECT postID FROM posts WHERE forumcat = '$forumid' ORDER BY postdate DESC LIMIT 1"));
	$getlast = $newf['postID'];
	mysql_query("UPDATE forumcats SET stat_lastpostid = '$getlast' WHERE forumID = '$forumid'");
	
	mysql_close($dbh);
	header("Location: forums.php?thread=$post_thread&page=last");
}
else
{
	include 'header.php';
	include 'sidebar.php';
	if (!isset($id) || (trim($id)==""))
	{
		$problem = "There is no post specified.";
		$back = "forums.php";
	}
	elseif (!isset($_POST['posttxt']) || (trim($_POST['posttxt'])==""))
	{
		$problem = "Your post content appears to be empty";
		$back = "forums.php?thread=".$post_thread;
	}
	elseif (!isset($_SESSION['lvl']) || ($_SESSION['lvl']==""))
	{
		$problem = "You don't seem to be logged in.";
		$back = "forums.php?thread=".$post_thread;
	}
	elseif (($_SESSION['lvl']>=35) && !($_SESSION['lvl']>$post_level) && !($poster == $_SESSION['usr']))
	{
		$problem = "You are only allowed to modify your own posts, or posts of users of lower level than you.";
		$back = "forums.php?thread=".$post_thread;
	}
	else
	{
		$problem = "You don't have permission to modify this post";
		$back = "forums.php?thread=".$post_thread;
	}
	include 'failure.php';
	include 'bottom.php';
}

?>