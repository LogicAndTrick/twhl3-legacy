<?
	include 'middle.php';
	if (!(isset($usr) && ($usr != "") && ($wiki_lvl > 0))) fail("You must be logged in, and have wiki access to do this.","index.php",true);
	$peditq = mysql_query("SELECT * FROM users WHERE userID = '$usr'");
	if (mysql_num_rows($peditq) == 0) fail("You don't exist.","index.php");
	$getcomm = mysql_real_escape_string($_GET['id']);
	if (!isset($getcomm) || !is_numeric($getcomm) || $getcomm == "") fail("No Comment Specified","wiki.php",true);
	
	if (isset($lvl) && ($lvl >= 20)) $ceditq = mysql_query("SELECT * FROM wikicomments LEFT JOIN users ON commentuser = userID WHERE commentID = '$getcomm'");
	else $ceditq = mysql_query("SELECT * FROM wikicomments LEFT JOIN users ON commentuser = userID WHERE commentID = '$getcomm' AND commentuser = '$usr'");
	
	if (mysql_num_rows($ceditq) == 0) fail("Comment not found.","wiki.php",true);
	
	$cedr = mysql_fetch_array($ceditq);
	$ctitle = mysql_real_escape_string($cedr['commenttitle']);
	$cuser = $cedr['commentuser'];
	$thenow=gmt("U");
	
	$valid = 0;
	if ($_SESSION['lvl'] >= 20) $valid = 1;
	
	$text=htmlfilter($_POST['comment'],true);
	
	mysql_query("DELETE FROM wikicomments WHERE commentID = '$getcomm'");
	mysql_query("UPDATE wikititles SET titlecoms = titlecoms-1 WHERE titleID = '$ctitle'");
	mysql_query("UPDATE users SET stat_wikicoms = stat_wikicoms-1 WHERE userID = '$cuser'");
	header("Location: wiki.php?id=".$ctitle);
?>