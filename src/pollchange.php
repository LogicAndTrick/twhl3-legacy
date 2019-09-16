<?
	include 'middle.php';
	
	if (!(isset($lvl) && ($lvl >= 40))) fail("You aren't logged in, or you don't have permission to do this.","index.php",true);
	
	$title = htmlfilter(trim($_POST['polltitle']));
	$subtitle = htmlfilter(trim($_POST['pollsubtitle']));
	$getpoll = mysql_real_escape_string($_GET['id']);
	
	if (!(isset($title) && $title != "" && isset($subtitle) && $subtitle != "" && is_numeric($getpoll))) fail("The title/subtitle is blank, or poll not found.","index.php",true);
	
	
	mysql_query("UPDATE polls SET polltitle = '$title', pollsubtitle = '$subtitle' WHERE pollID = '$getpoll'");
	
	header("Location: polledit.php");
?>