<?
	include 'middle.php';
	
	if (!(isset($usr) && ($usr != ""))) fail("You must be logged in to view this page.","index.php",true);
	if (!(isset($lvl) && ($lvl >= 40))) fail("You must be an admin to view this page! Get the hell out!","index.php",true);
	
	if (!(isset($_GET['id']) && $_GET['id'] != "")) fail("No ban specified.","index.php",true);
	$ban_id = mysql_real_escape_string($_GET['id']);
	$peditq = mysql_query("SELECT * FROM bans WHERE banID = '$ban_id'");
	if (mysql_num_rows($peditq) == 0) fail("This ban doesn't exist.","index.php",true);
	
	$peditr = mysql_fetch_array($peditq);
	$userid = $peditr['userID'];
	
	if (isset($_GET['time']))
	{
		if (!is_numeric($_POST['bantime'])) fail("Invalid ban time.","user.php?manage=$userid&ban",true);
		if (!($_POST['banunits']==3600 || $_POST['banunits']==86400 || $_POST['banunits']==604800 || $_POST['banunits']==-1)) fail("Invalid ban units.","user.php?manage=$userid&ban",true);
		$bantime = $_POST['bantime'] * $_POST['banunits'];
		if ($bantime >= 0) $bantime += gmt("U");
		mysql_query("UPDATE bans SET time = '$bantime' WHERE banID = '$ban_id'");
	}
	elseif (isset($_GET['reason']))
	{
		$banreason = htmlfilter($_POST['banreason']);
		mysql_query("UPDATE bans SET reason = '$banreason' WHERE banID = '$ban_id'");
	}
	elseif (isset($_GET['remove']))
	{
		mysql_query("DELETE FROM bans WHERE banID = '$ban_id'");
	}
	
	header("Location: user.php?manage=$userid&ban");
?>