<?php	

	include 'middle.php';

	if (!(isset($usr) && ($usr != "") && ($wiki_lvl > 0))) fail("You must be logged in, and have wiki access to do this.","index.php",true);
	if (!(isset($_GET['id']) && ($_GET['id'] != "") && (is_numeric($_GET['id'])))) fail("No entry specified.","wiki.php",true);
	$getenty = mysql_real_escape_string($_GET['id']);
	$entyq = mysql_query("SELECT * FROM wikititles WHERE titleID = '$getenty'");
	if (mysql_num_rows($entyq) == 0) fail("Entry not found.","wiki.php",true);
	if (!(isset($_POST['comment']) && (trim($_POST['comment']) != ""))) fail("Cannot submit empty comments.","wiki.php?id=$getenty",true);
	
	$entyr = mysql_fetch_array($entyq);
	$thenow=gmt("U");
	$text=htmlfilter($_POST['comment'],true);
	$verify = ($lvl>=20)?1:0;
	
	mysql_query("INSERT INTO wikicomments (commenttitle,commentuser,commentdate,commentcontent,commentverified) VALUES ('$getenty','$usr','$thenow','$text','$verify')");
	mysql_query("UPDATE users SET stat_wikicoms = stat_wikicoms+1 WHERE userID = '$usr'");
	mysql_query("UPDATE wikititles SET titlecoms = titlecoms+1 WHERE titleID = '$getenty'");
	
	header("Location: wiki.php?id=$getenty");
?>