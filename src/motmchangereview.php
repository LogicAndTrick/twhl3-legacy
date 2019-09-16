<?
if ($_POST['revid']!="" && isset($_GET['id']) && $_GET['id'] != "")
{
	include 'middle.php';
	
	$motmid=mysql_real_escape_string($_GET['id']);
	
	$revid=mysql_real_escape_string($_POST['revid']);
	$arch=mysql_real_escape_string($_POST['arch']);
	$tex=mysql_real_escape_string($_POST['tex']);
	$amb=mysql_real_escape_string($_POST['amb']);
	$light=mysql_real_escape_string($_POST['light']);
	$game=mysql_real_escape_string($_POST['game']);
	$total=mysql_real_escape_string($_POST['total']);
	//$content=$_POST['content'];
	$content = mysql_real_escape_string(trim(stripslashes(str_replace("\n","<br />",htmlspecialchars($_POST['content'])))));
	
	mysql_query("UPDATE motmreviews SET
	reviewer = '$revid', arch = '$arch', tex = '$tex', amb = '$amb', light = '$light', game = '$game', total = '$total', content = '$content'
	WHERE reviewID = $motmid");
	
	$row=mysql_fetch_array(mysql_query("SELECT motm FROM motmreviews WHERE reviewID = $motmid"));
	$newid=$row['motm'];
	mysql_close($dbh);
	header("Location: motmeditlist.php?id=$newid");
}
?>