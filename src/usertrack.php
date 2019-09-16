<?php

include 'middle.php';

$gettrack=mysql_real_escape_string($_GET['id']);

if (isset($_SESSION['lvl']) && $_SESSION['lvl']!="")
{	
	// see if this user is already tracking this thread
	$tracking = mysql_num_rows(mysql_query("SELECT * FROM threadtracking WHERE trackuser = '$usr' AND trackID = '$gettrack'"));
	if ($tracking > 0)
	{
		mysql_query("DELETE FROM threadtracking WHERE trackid = '$gettrack'");
		mysql_query("UPDATE users SET stat_ttracking = stat_ttracking-1 WHERE userID = '$usr'");
	}
	mysql_close($dbh);
	header("Location: user.php?control&tracking");
}
else
{
	include 'header.php';
	include 'sidebar.php';
	if (!isset($_SESSION['lvl']) || ($_SESSION['lvl']==""))
	{
		$problem = "You need to be logged in to do this.";
		$back = "forums.php?id=".$thread;
	}
	include 'failure.php';
	include 'bottom.php';
}

?>