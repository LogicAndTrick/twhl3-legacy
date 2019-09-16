<?
if ($_POST['mapid']!="" && isset($_GET['id']) && $_GET['id'] != "")
{
	include 'middle.php';
	
	$motmid=mysql_real_escape_string($_GET['id']);

	$mapid=mysql_real_escape_string($_POST['mapid']);
	$month=mysql_real_escape_string($_POST['date']);
	$numreviews=mysql_real_escape_string($_POST['numreviews']);
	$thumb=mysql_real_escape_string($_POST['thumb']);
	$image=mysql_real_escape_string($_POST['image']);
	$arch=mysql_real_escape_string($_POST['arch']);
	$tex=mysql_real_escape_string($_POST['tex']);
	$amb=mysql_real_escape_string($_POST['amb']);
	$light=mysql_real_escape_string($_POST['light']);
	$game=mysql_real_escape_string($_POST['game']);
	$total=mysql_real_escape_string($_POST['total']);
	
	mysql_query("UPDATE motm SET
	date = '$month', map = '$mapid',reviewers = '$numreviews', thumb = '$thumb', image = '$image', arch = '$arch', tex = '$tex', amb = '$amb', light = '$light', game = '$game', total = '$total'
	WHERE motmID = $motmid");
	
	mysql_close($dbh);
	header("Location: motmeditlist.php?id=$motmid");
}
?>