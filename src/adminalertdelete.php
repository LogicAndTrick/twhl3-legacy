<?php

include 'middle.php';

$getalert=mysql_real_escape_string($_GET['id']);

if (isset($_SESSION['lvl']) && $_SESSION['lvl']>=40)
{	
	// see if this alert is deletable by this user
	$allowed = mysql_num_rows(mysql_query("SELECT * FROM alertadmin LEFT JOIN alerttypes ON alerttype = alerttypeID WHERE typelevel <= '$lvl' AND alertID = '$getalert'"));
	if ($allowed > 0)
	{
		mysql_query("DELETE FROM alertadmin WHERE alertID = '$getalert'");
	}
	mysql_close($dbh);
	header("Location: admin.php?alerts");
}
else
{
	include 'header.php';
	include 'sidebar.php';
	if (!isset($_SESSION['lvl']) || ($_SESSION['lvl']<40))
	{
		$problem = "You need to be logged in to do this.";
		$back = "forums.php?id=".$thread;
	}
	include 'failure.php';
	include 'bottom.php';
}

?>