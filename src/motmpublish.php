<?
	include 'middle.php';
	if (isset($lvl) && (($lvl >= 30) || ($usr == 2236)))
	{
		$motmid=mysql_real_escape_string($_GET['id']);
		$row=mysql_fetch_array(mysql_query("SELECT * FROM motm WHERE motmID = $motmid"));
		mysql_query("UPDATE maps SET gotmotm = 1 WHERE mapID = " . $row["map"]);
		header("Location: motm.php?id=$motmid");
	}
?>
Go away.