<?php	

	include 'middle.php';

	if (!(isset($usr) && ($usr != ""))) fail("You must be logged in to do this.","index.php",true);
	if (!(isset($_GET['id']) && ($_GET['id'] != "") && (is_numeric($_GET['id'])))) fail("No map specified.","vault.php",true);
	$getmap = mysql_real_escape_string($_GET['id']);
	$mapq = mysql_query("SELECT * FROM maps WHERE mapID = '$getmap'");
	if (mysql_num_rows($mapq) == 0) fail("Map not found.","vault.php",true);
	if (!(isset($_POST['comment']) && (trim($_POST['comment']) != ""))) fail("Cannot submit empty comments.","vault.php?map=",true);
	
	$mapr = mysql_fetch_array($mapq);
	$ownerid = $mapr['owner'];
	$pmcomm = $mapr['pmcomment'];
	$mapname = $mapr['name'];
	
	$mapid=mysql_real_escape_string($_GET['id']);
	$user=$_SESSION['usr'];
	$thenow=gmt("U");
	$rating=0;
	if (isset($_POST['rating']) && $_POST['rating']!="" && $mapr['allowrating']==1)
	{
		$rating=$_POST['rating'];
		if ($rating > 5 || $rating < 1 || !is_numeric($rating)) $rating = 0;
	}
	$text=htmlfilter($_POST['comment'],true);
	
	$valid = true;
	$serror = $_FILES['attach']['error'];
	$sname = $_FILES['attach']['name'];
	$stype = $_FILES['attach']['type'];
	$ssize = $_FILES['attach']['size'];
	$stmp = $_FILES['attach']['tmp_name'];
	if (!$sname || !isset($sname) || $sname == "") $valid = false;
	if ($serror > 0) $valid = false;
	if (!is_uploaded_file($stmp)) $valid = false;
	if (!eregi('.zip',substr($sname,-4)) && !eregi('.rar',substr($sname,-4))) $valid = false;
	if ($ssize > 2097152) $valid = false;
	$attach = 0;
	$rar = 0;
	if (eregi('.rar',substr($sname,-4))) $rar = 1;
	if ($valid)
	{
		move_uploaded_file($ftmp, "mapvault/".$newid."_c".substr($fname,-4)) or die ('Sorry, server is being stupid. Please retry your upload.');
		$attach = 1;
	}
	
	mysql_query("INSERT INTO mapcomments (map,poster,commtime,commtext,rating,attachment,attachisrar) VALUES ('$mapid','$user','$thenow','$text','$rating','$attach','$rar')");
	
	if ($rating > 0)
	{
		mysql_query("UPDATE maps SET rating = rating + $rating, ratings = ratings + 1 WHERE mapID = '$mapid' LIMIT 1");
	}
	if ($pmcomm == 1 && $ownerid != $usr)
	{
		mysql_query("INSERT INTO pminbox (pmto,pmfrom,pmtime,pmsubject,pmtext,isnew) VALUES ('$ownerid','$usr','$thenow','Automated Message From the Vault','I have just commented on your map, $mapname! [url=http://www.twhl.info/vault.php?map=$getmap]Click here to see it[/url].','1')");
	}
	
	mysql_query("UPDATE users SET stat_mvcoms = stat_mvcoms+1 WHERE userID = '$user'");
	
	header("Location: vault.php?map=$mapid");
?>