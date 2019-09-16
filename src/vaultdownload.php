<?
include 'middle.php';

$id = mysql_real_escape_string($_GET['download']);

mysql_query("UPDATE maps SET downloads = downloads+1 WHERE mapID = '$id'");
$fileloc = mysql_query("SELECT file,israr FROM maps WHERE mapID = '$id'");
if (mysql_num_rows($fileloc) == 1)
{
	$fl = mysql_fetch_array($fileloc);
	if (empty($fl["file"])) header ("Location: mapvault/".$id.".".(($fl["israr"] == 1)?'rar':'zip'));
	else header ("Location: ".$fl['file']);
}
else fail("Download file not found","vault.php?map=$id",true);

?>