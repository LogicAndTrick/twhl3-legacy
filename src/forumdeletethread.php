<?php

include 'middle.php';
include 'forumpostmanagement.php';

$id=mysql_real_escape_string($_GET['id']);

if (isset($id) && (trim($id)!="") && is_numeric($id) &&
isset($_SESSION['lvl']) && ($_SESSION['lvl']>=35))
{
	
	$forumnm = mysql_query("SELECT * FROM threads WHERE threadID = '$id'");
	$fmarray = mysql_fetch_array($forumnm);
	$forumid = $fmarray['forumcat'];

	mysql_query("DELETE FROM threads WHERE threadID='$id'");
	
	mysql_query("UPDATE forumcats SET stat_topics = stat_topics-1 WHERE forumID = '$forumid'");

	$deletedrows=mysql_num_rows(mysql_query("SELECT * FROM posts WHERE threadid='$id'"));
	
	delete_thread_posts($id);
	//mysql_query("DELETE FROM posts WHERE threadid='$id'");
	
	$newf = mysql_fetch_array(mysql_query("SELECT postID FROM posts WHERE forumcat = '$forumid' ORDER BY postID DESC LIMIT 1"));
	$getlast = $newf['postID'];
	//$nump = mysql_fetch_array(mysql_query("SELECT count(*) AS cnt FROM posts WHERE forumcat = '$forumid'"));
	//$num = $nump['cnt'];
	mysql_query("UPDATE forumcats SET stat_lastpostid = '$getlast', stat_posts = stat_posts-$deletedrows WHERE forumID = '$forumid'");
  
	mysql_close($dbh);
	header("Location: forums.php?id=$forumid");

}
else
{
	include 'header.php';
	include 'sidebar.php';
	if (!isset($_SESSION['lvl']) || ($_SESSION['lvl']<35))
	{
		$problem = "You don't have permission to view this page.";
		$back = "forums.php";
	}
	elseif (!isset($id) || ($id=="") || !is_numeric($id))
	{
		$problem = "There is no thread specified.";
		$back = "forums.php";
	}
	include 'failure.php';
	include 'bottom.php';
}


?>