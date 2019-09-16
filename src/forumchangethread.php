<?php

include 'middle.php';

$id=mysql_real_escape_string($_GET['id']);
	
if (isset($id) && (trim($id)!="") && is_numeric($id) &&
isset($_POST['name']) && (trim($_POST['name'])!="") &&
isset($_POST['newid']) && (trim($_POST['newid'])!="") && is_numeric($_POST['newid']) &&
isset($_SESSION['lvl']) && ($_SESSION['lvl']>=35))
{
	$newname = htmlfilter($_POST['name']);
	$newauthor = mysql_real_escape_string($_POST['newid']);
	$newforum = mysql_real_escape_string($_POST['newforum']);
	$stick = $_POST['sticky'];
	$closed = $_POST['closed'];
	
	$sticky = 0;
	if ($stick) $sticky = 1;
	
	$open = 1;
	if ($closed) $open = 0;
	
	mysql_query("UPDATE threads SET forumcat='$newforum', name='$newname', ownerid='$newauthor', prop_open='$open', prop_sticky='$sticky' WHERE threadID='$id'");
	
	mysql_close($dbh);
	header("Location: forums.php?thread=$id");
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
	else
	{
		$problem = "You don't have permission to modify this post";
		$back = "forums.php?thread=".$post_thread;
	}
	include 'failure.php';
	include 'bottom.php';
}

?>