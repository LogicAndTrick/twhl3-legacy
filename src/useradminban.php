<?
	include 'middle.php';
	
	if (!(isset($usr) && ($usr != ""))) fail("You must be logged in to view this page.","index.php",true);
	if (!(isset($lvl) && ($lvl >= 40))) fail("You must be an admin to view this page! Get the hell out!","index.php",true);
	
	if (!(isset($_GET['id']) && $_GET['id'] != "")) fail("No user specified.","index.php",true);
	$user_id = mysql_real_escape_string($_GET['id']);
	$peditq = mysql_query("SELECT * FROM users WHERE userID = '$user_id'");
	if (mysql_num_rows($peditq) == 0) fail("This user doesn't exist.","index.php",true);
	
	$peditr = mysql_fetch_array($peditq);
	$banip = $peditr['ipadd'];
	
	if (!is_numeric($_POST['bantime'])) fail("Invalid ban time.","user.php?manage=$user_id&ban",true);
	if (!($_POST['banunits']==3600 || $_POST['banunits']==86400 || $_POST['banunits']==604800 || $_POST['banunits']==-1)) fail("Invalid ban units.","user.php?manage=$user_id&ban",true);
	
	$bantime = $_POST['bantime'] * $_POST['banunits'];
	if ($bantime >= 0) $bantime += gmt("U");
	
	$banreason = htmlfilter($_POST['banreason']);
	
	mysql_query("INSERT INTO bans (userID,IP,time,reason) VALUES ('$user_id','$banip','$bantime','$banreason')");
	
	header("Location: user.php?manage=$user_id&ban");
?>