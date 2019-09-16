<?
if ($_POST['mapid']!="")
{
	include 'middle.php';

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
	
	mysql_query("INSERT INTO motm (date,map,reviewers,thumb,image,arch,tex,amb,light,game,total)
	VALUES
	('$month','$mapid','$numreviews','$thumb','$image','$arch','$tex','$amb','$light','$game','$total')");
	
	$row=mysql_fetch_array(mysql_query("SELECT motmID FROM motm ORDER BY motmID DESC LIMIT 1"));
	$newid=$row['motmID'];
	
	for ($i = 0; $i < $numreviews; $i++)
		mysql_query("INSERT INTO motmreviews (motm) VALUES ('$newid')");
	
	mysql_close($dbh);
	header("Location: motmeditlist.php?id=$newid");
}
?>