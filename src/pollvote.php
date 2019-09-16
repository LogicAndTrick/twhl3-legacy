<?php

include 'middle.php';

$vote=mysql_real_escape_string($_POST['pollvote']);
$ret=mysql_real_escape_string($_POST['return']);

if (isset($_SESSION['lvl']) && $_SESSION['lvl']!="" &&
isset($ret) && (trim($ret)!="") &&
isset($vote) && (trim($vote)!="") && is_numeric($vote))
{
	// user can only vote once
	$last = -1;
	$polluser=mysql_query("SELECT * FROM users WHERE userID = '$usr'");
	if (mysql_num_rows($polluser) > 0)
	{
		$pur = mysql_fetch_array($polluser);
		$last = $pur['lastvote'];
	}
	
	// make sure poll exists
	$pollid = -1;
	$pollitemq = mysql_query("SELECT * FROM pollitems WHERE itemID = '$vote'");
	$numitems = mysql_num_rows($pollitemq);
	if ($numitems > 0)  {
		$pidrow = mysql_fetch_array($pollitemq);
		$pollid = $pidrow['itempoll'];
	}
	
	//make sure poll is active
	$isactive = false;
	$pollactq = mysql_query("SELECT * FROM polls WHERE pollID = '$pollid' AND isactive = '1'");
	if (mysql_num_rows($pollactq) > 0) $isactive = true;
	
	$return = "index.php";
	if (($last < $pollid) && ($last != -1) && ($pollid != -1) && ($isactive))
	{
		mysql_query("UPDATE users SET lastvote = '$pollid' WHERE userID = '$usr'");
		mysql_query("UPDATE pollitems SET votes = votes+1 WHERE itemID = '$vote'");
		$return = $ret;
	}
	mysql_close($dbh);
	header("Location: $return");

}
else
{
	include 'header.php';
	include 'sidebar.php';
	if (!isset($vote) || (trim($vote)=="") || !is_numeric($vote))
	{
		$problem = "There is no poll specified.";
		$back = "forums.php";
	}
	elseif (!isset($ret) || (trim($ret)==""))
	{
		$problem = "Cannot identify return value.";
		$back = "forums.php?thread=".$thd;
	}
	elseif (!isset($_SESSION['lvl']) || ($_SESSION['lvl']==""))
	{
		$problem = "You need to be logged in to vote.";
		$back = "forums.php?thread=".$thd;
	}
	include 'failure.php';
	include 'bottom.php';
}

?>