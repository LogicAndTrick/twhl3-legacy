<?php

include 'middle.php';

$getalert=mysql_real_escape_string($_GET['id']);

if (isset($_SESSION['lvl']) && $_SESSION['lvl']!="")
{	
	// see if this alert belongs to this user
	$owned = mysql_num_rows(mysql_query("SELECT * FROM alertuser WHERE alertuser = '$usr' AND alertID = '$getalert'"));
	if ($owned > 0)
	{
		mysql_query("DELETE FROM alertuser WHERE alertID = '$getalert'");
	}
	mysql_close($dbh);
	header("Location: user.php?control&alerts");
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