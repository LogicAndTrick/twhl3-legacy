<?
	//login and other user stuffs
	include 'functions.php';
	include 'logins.php';
	
	$getent = mysql_real_escape_string($_GET['id']);
	if (!isset($getent) || ($getent == "")) fail("No entry specified!.","wiki.php",true);
	
	$entq = mysql_query("SELECT * FROM wikientries LEFT JOIN wikititles ON entrytitle = titleID WHERE titleID = '$getent' ORDER BY entryrevision DESC LIMIT 1");
	if (mysql_num_rows($entq) == 0) fail("Entry not found!.","wiki.php",true);
	
	if (!(isset($usr) && ($usr != "") && ($wiki_lvl > 0))) fail("You must be logged in, and have wiki access to do this.","wiki.php?request=".$getent,true);
	
	$newtitle = htmlfilter($_POST['newtitle']);
	$delreas = htmlfilter($_POST['delreas']);
	$reqtype = htmlfilter($_POST['choose']);
	
	if ($reqtype != 1 && $reqtype != 2) fail("Invalid request code.","wiki.php?request=".$getent,true);
	if ($reqtype == 1 && ($newtitle == "" || !isset($newtitle))) fail("Can't submit a blank title.","wiki.php?request=".$getent,true);
	if ($reqtype == 2 && ($delreas == "" || !isset($delreas))) fail("Can't submit a blank reason.","wiki.php?request=".$getent,true);
	
	$requesttitle = $getent;
	$requesttype = $reqtype;
	$requestuser = $usr;
	$requestdetails = ($reqtype == 1)?$newtitle:$delreas;
	$requestdate = gmt("U");
	
	mysql_query("INSERT INTO wikirequests (requesttitle,requesttype,requestuser,requestdetails,requestdate) VALUES ('$requesttitle','$requesttype','$requestuser','$requestdetails','$requestdate')");
	
	header("Location: wiki.php?reqdone=".$getent);
?>