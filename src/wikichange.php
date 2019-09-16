<?
	//login and other user stuffs
	include 'functions.php';
	include 'logins.php';
	
	$getent = mysql_real_escape_string($_GET['id']);
	if (!isset($getent) || ($getent == "")) fail("No entry specified!.","wiki.php",true);
	$entq = mysql_query("SELECT * FROM wikientries LEFT JOIN wikititles ON entrytitle = titleID WHERE titleID = '$getent' ORDER BY entryrevision DESC LIMIT 1");
	if (mysql_num_rows($entq) == 0) fail("Entry not found!.","wiki.php",true);
	$entr = mysql_fetch_array($entq);
	$entrytitle = mysql_real_escape_string($entr['entrytitle']);
	$entryrevision = $entr['entryrevision']+1;
	if (!(isset($usr) && ($usr != "") && ($wiki_lvl > 0))) fail("You must be logged in, and have wiki access to do this.","wiki.php?id=".$getent,true);
	$entrycontent = htmlfilter($_POST['content']);
	if (!isset($entrycontent) || ($entrycontent == "")) fail("Can't submit blank edits!.","wiki.php?id=".$getent,true);
	$entrydetails = htmlfilter($_POST['details']);
	if (!isset($entrydetails) || ($entrydetails == "")) $entrydetails = "Minor edit";
	$entryisactive = 1;
	$entrydate = gmt("U");
	$entryuser = $usr;
	$entryverified = ($lvl>=20)?'1':'0';
	
	mysql_query("UPDATE wikientries SET entryisactive = '0' WHERE entrytitle = '$entrytitle'");
	if ($lvl >= 20) mysql_query("UPDATE wikientries SET entryverified = '1' WHERE entrytitle = '$entrytitle'");
	mysql_query("INSERT INTO wikientries (entrytitle,entryuser,entrycontent,entryrevision,entrydetails,entryisactive,entryverified,entrydate) VALUES ('$entrytitle','$entryuser','$entrycontent','$entryrevision','$entrydetails','$entryisactive','$entryverified','$entrydate')");
	mysql_query("UPDATE wikititles SET titlerevisions = titlerevisions+1, titledate = '$entrydate' WHERE titletitle = '$entrytitle'");
	header("Location: wiki.php?id=".$entrytitle);
?>