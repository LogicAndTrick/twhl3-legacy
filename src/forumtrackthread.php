<?php

include 'middle.php';

$thread=mysql_real_escape_string($_GET['id']);

$valid = true;

if (isset($thread) && (trim($thread)!="") && is_numeric($thread) &&
isset($_SESSION['lvl']) && $_SESSION['lvl']!="")
{	
	$thenow = gmt("U");
	// see if this user is already tracking this thread
	$alreadytracking = mysql_num_rows(mysql_query("SELECT * FROM threadtracking WHERE trackuser = '$usr' AND trackthread = '$thread'"));
	// see if this user is already tracking too many threads
	$numtracking = mysql_num_rows(mysql_query("SELECT * FROM threadtracking WHERE trackuser = '$usr'"));
	//if they aren't, add track
	if ($alreadytracking == 0 && $numtracking < 10)
	{
		mysql_query("INSERT INTO threadtracking (trackuser,trackthread,trackdate,isnew) VALUES ('$usr','$thread','$thenow','0')");
		mysql_query("UPDATE users SET stat_ttracking = stat_ttracking+1 WHERE userID = '$usr'");
	}
	elseif ($alreadytracking > 0)
	{
		mysql_query("DELETE FROM threadtracking WHERE trackuser = '$usr' AND trackthread = '$thread'");
		mysql_query("UPDATE users SET stat_ttracking = stat_ttracking-1 WHERE userID = '$usr'");
	}
	else
	{
		// max tracks = 10.
		$valid = false;
	}
	
	if ($valid)
	{
		mysql_close($dbh);
		header("Location: forums.php?thread=$thread");
	}
}
else
{
	$valid = false;
}
if (!$valid)
{
	include 'header.php';
	include 'sidebar.php';
	if (!isset($thread) || (trim($thread)=="") || (!is_numeric($thread)))
	{
		$problem = "There is no thread specified.";
		$back = "forums.php";
	}
	elseif (!isset($_SESSION['lvl']) || ($_SESSION['lvl']==""))
	{
		$problem = "You need to be logged in to track threads.";
		$back = "forums.php?id=".$thread;
	}
	else
	{
		$problem = "You can only track up to 10 threads at once";
		$back = "forums.php?id=".$thread;
	}
	include 'failure.php';
	include 'bottom.php';
}

?>