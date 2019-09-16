<?php	

	include 'middle.php';

	if (!(isset($usr) && ($usr != ""))) fail("You must be logged in to do this.","index.php",true);
	if (!(isset($_GET['id']) && ($_GET['id'] != "") && (is_numeric($_GET['id'])))) fail("No news specified.","news.php",true);
	$getnews = mysql_real_escape_string($_GET['id']);
	$newsq = mysql_query("SELECT * FROM news WHERE newsID = '$getnews'");
	if (mysql_num_rows($newsq) == 0) fail("News not found.","news.php",true);
	if (!(isset($_POST['comment']) && (trim($_POST['comment']) != ""))) fail("Cannot submit empty comments.","news.php?id=$getnews",true);
	
	$newsr = mysql_fetch_array($newsq);
	$thenow=gmt("U");
	$text=htmlfilter($_POST['comment'],true);
	
	mysql_query("INSERT INTO newscomments (commnews,commuser,commdate,commtext) VALUES ('$getnews','$usr','$thenow','$text')");
	mysql_query("UPDATE users SET stat_ncoms = stat_ncoms+1 WHERE userID = '$usr'");
	mysql_query("UPDATE news SET stat_coms = stat_coms+1 WHERE newsID = '$getnews'");
	
	header("Location: news.php?id=$getnews");
?>