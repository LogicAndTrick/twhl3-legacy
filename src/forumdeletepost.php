<?php

include 'middle.php';
include 'forumpostmanagement.php';

$delete=mysql_real_escape_string($_GET['id']);

if (isset($delete) && (trim($delete)!="") && is_numeric($delete) &&
isset($_SESSION['lvl']) && ($_SESSION['lvl']>=35))
{
	
	$forumnm = mysql_query("SELECT * FROM posts WHERE postID = '$delete'") or die("Unable to verify user because : " . mysql_error());
	$fmarray = mysql_fetch_array($forumnm);
	$forumid = $fmarray['forumcat'];
	$threadid = $fmarray['threadid'];
	
	$redir = "forums.php?thread=$threadid";

	delete_post($delete);
	//mysql_query("DELETE FROM posts WHERE postID='$delete'");
	
	$checkempty = mysql_query("SELECT postID FROM posts WHERE threadid = '$threadid' ORDER BY postID DESC LIMIT 1");
	if (mysql_num_rows($checkempty) == 0)
	{
		mysql_query("DELETE FROM threads WHERE threadID='$threadid'");
		$redir = "forums.php?id=$forumid";
		mysql_query("UPDATE forumcats SET stat_topics = stat_topics-1 WHERE forumID = '$forumid'");
	}
	else
	{
		$getlast = mysql_fetch_array($checkempty);
		$getlast2 = $getlast['postID'];
		mysql_query("UPDATE threads SET stat_lastpostid = '$getlast2', stat_replies = stat_replies-1 WHERE threadID = '$threadid'");
	}
	
	$newf = mysql_fetch_array(mysql_query("SELECT postID FROM posts WHERE forumcat = '$forumid' ORDER BY postID DESC LIMIT 1"));
	$getlast = $newf['postID'];
	mysql_query("UPDATE forumcats SET stat_lastpostid = '$getlast', stat_posts = stat_posts-1 WHERE forumID = '$forumid'");
  
	mysql_close($dbh);
	header("Location: $redir");

}
else
{
	include 'header.php';
	include 'sidebar.php';
	if (!isset($_SESSION['lvl']) || ($_SESSION['lvl']<35))
	{
		$problem = "You don't have permission to view this page.";
		$back = "forums.php?thread=".$post_thread;
	}
	elseif (!isset($delete) || ($delete=="") || !is_numeric($delete))
	{
		$problem = "There is no post specified.";
		$back = "forums.php";
	}
	include 'failure.php';
	include 'bottom.php';
}


?>