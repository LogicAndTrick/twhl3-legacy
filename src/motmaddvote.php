<?
	include 'middle.php';
	
	$getmap = mysql_real_escape_string($_GET['id']);
	
	if (!(isset($getmap) && ($getmap != "") && is_numeric($getmap))) fail("Map not found.","index.php",true);
	if (!(isset($usr) && ($usr != ""))) fail("You must be logged in to do this.","vault.php?id=$getmap",true);
	
	$mapq = mysql_query("SELECT * FROM maps WHERE mapID = '$getmap' AND cat = '2' AND owner != '$usr'");
	
	if (mysql_num_rows($mapq) == 0) fail("You cannot vote for your own map, or map not found.","vault.php?map=$getmap",true);
	
	$mapr = mysql_fetch_array($mapq);
	$mapdate = $mapr['postdate'];
	$mapmonth = date("n",$mapdate);
	$mapyear = date("Y",$mapdate);
	$mapmonthyear = date("n Y",$mapdate);
	$lastmonth = date("n Y",strtotime("last month",gmt("U")));
	
	if (!($lastmonth == $mapmonthyear)) fail("You can only vote for last month's maps.","vault.php?id=$getmap",true);
	
	$thenow = gmt("U");
	
	mysql_query("DELETE FROM motmvotes WHERE voteuser = '$usr' AND votemonth = '$mapmonth' AND voteyear = '$mapyear' LIMIT 1");
	mysql_query("INSERT INTO motmvotes (voteuser,votemap,votemonth,voteyear,votetime) VALUES ('$usr','$getmap','$mapmonth','$mapyear','$thenow')");
	
	header("Location: vault.php?map=$getmap");
?>