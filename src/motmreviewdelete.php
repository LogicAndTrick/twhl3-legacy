<?
	include 'middle.php';
	if (isset($lvl) && (($lvl >= 30) || ($usr == 2236)))
	{
		$revid=mysql_real_escape_string($_GET['id']);
		$row=mysql_fetch_array(mysql_query("SELECT * FROM motmreviews WHERE reviewID = $revid"));
		$motmid = $row['motm'];
		mysql_query("DELETE FROM motmreviews WHERE reviewID = $revid");
		header("Location: motmeditlist.php?id=$motmid");
	}
?>
Go away.