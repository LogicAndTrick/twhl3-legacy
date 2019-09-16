<?
	//login and other user stuffs
	include 'functions.php';
	include 'logins.php';
	
	$getent = mysql_real_escape_string($_GET['id']);
	if (!isset($getent) || ($getent == "")) fail("No entry specified!.","wiki.php",true);
	$entq = mysql_query("SELECT * FROM wikientries LEFT JOIN wikititles ON entrytitle = titleID WHERE entryID = '$getent' AND entryisactive = '0' LIMIT 1");
	if (mysql_num_rows($entq) == 0) fail("Entry not found, or cannot be reverted.","wiki.php",true);
	$entr = mysql_fetch_array($entq);
	$entrytitle = $entr['entrytitle'];
	$revertrevision = $entr['entryrevision'];
	$entrycontent = mysql_real_escape_string($entr['entrycontent']);
	$lentq = mysql_query("SELECT * FROM wikientries LEFT JOIN wikititles ON entrytitle = titleID WHERE titleID = '$entrytitle' ORDER BY entryrevision DESC LIMIT 1");
	if (mysql_num_rows($lentq) == 0) fail("Entry not found!","wiki.php",true);
	$lentr = mysql_fetch_array($lentq);
	$entryrevision = $lentr['entryrevision']+1;
	if (!(isset($usr) && ($usr != "") && ($wiki_lvl > 0))) fail("You must be logged in, and have wiki access to do this.","wiki.php?id=".$getent,true);
	$entrydetails = htmlfilter($_POST['details']);
	if (!isset($entrydetails) || ($entrydetails == "")) $entrydetails = "Spam";
	$entrydetails = "Reverted to #".$revertrevision.": ".$entrydetails;
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