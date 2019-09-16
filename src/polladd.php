<?
	include 'middle.php';
	
	if (!(isset($lvl) && ($lvl >= 40))) fail("You aren't logged in, or you don't have permission to do this.","index.php",true);
	
	$title = htmlfilter(trim($_POST['polltitle']));
	$subtitle = htmlfilter(trim($_POST['pollsubtitle']));
	$item1 = htmlfilter(trim($_POST['pollitem1']));
	$item2 = htmlfilter(trim($_POST['pollitem2']));
	$item3 = htmlfilter(trim($_POST['pollitem3']));
	$item4 = htmlfilter(trim($_POST['pollitem4']));
	$item5 = htmlfilter(trim($_POST['pollitem5']));
	$item6 = htmlfilter(trim($_POST['pollitem6']));
	
	if (!(isset($title) && $title != "" && isset($subtitle) && $subtitle != "")) fail("The title and/or the subtitle was empty.","polledit.php",true);
	
	mysql_query("UPDATE polls SET isactive = 0");
	
	mysql_query("INSERT INTO polls (polltitle,pollsubtitle,isactive) VALUES ('$title','$subtitle','1')");
	
	$pollidr = mysql_fetch_array(mysql_query("SELECT * FROM polls ORDER BY pollID DESC LIMIT 1"));
	$pollid = $pollidr['pollID'];
	
	if (isset($item1) && $item1 != "")
		mysql_query("INSERT INTO pollitems (itempoll,item,votes) VALUES ('$pollid','$item1','0')");
	if (isset($item2) && $item2 != "")
		mysql_query("INSERT INTO pollitems (itempoll,item,votes) VALUES ('$pollid','$item2','0')");
	if (isset($item3) && $item3 != "")
		mysql_query("INSERT INTO pollitems (itempoll,item,votes) VALUES ('$pollid','$item3','0')");
	if (isset($item4) && $item4 != "")
		mysql_query("INSERT INTO pollitems (itempoll,item,votes) VALUES ('$pollid','$item4','0')");
	if (isset($item5) && $item5 != "")
		mysql_query("INSERT INTO pollitems (itempoll,item,votes) VALUES ('$pollid','$item5','0')");
	if (isset($item6) && $item6 != "")
		mysql_query("INSERT INTO pollitems (itempoll,item,votes) VALUES ('$pollid','$item6','0')");
	
	header("Location: polledit.php");
?>