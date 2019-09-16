<?php

include 'middle.php';

$thd=mysql_real_escape_string($_GET['id']);

if (isset($_SESSION['lvl']) &&
isset($_POST['posttxt']) && (trim($_POST['posttxt'])!="") &&
isset($thd) && (trim($thd)!="") && is_numeric($thd))
{

	$posttext=htmlfilter($_POST['posttxt'],true);
	$postdate=gmt(U);
	
	$gettingcat = mysql_fetch_array(mysql_query("SELECT forumcat FROM threads WHERE threadID = $thd LIMIT 1"));
	$cat = $gettingcat['forumcat'];
	
	mysql_query("INSERT INTO alertadmin (alertuser,alerttype,alertdate,alertlink,alertcat,alertthread,alertcontent,isnew)
	VALUES
	('$usr','9','$postdate','$thd','$cat','$thd','$posttext','1')");
	
	mysql_close($dbh);
	header("Location: forums.php?thread=$thd");

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
		$back = "forums.php?threadbump=".$thd;
	}
	elseif (!isset($_SESSION['lvl']) || ($_SESSION['lvl']==""))
	{
		$problem = "You don't seem to be logged in.";
		$back = "forums.php?threadbump=".$thd;
	}
	include 'failure.php';
	include 'bottom.php';
}

?>