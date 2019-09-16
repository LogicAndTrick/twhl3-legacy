<?
	include 'middle.php';
	
	if (!(isset($lvl) && ($lvl >= 35))) fail("You aren't logged in, or you don't have permission to do this.","index.php",true);
	if (!(isset($_POST['title']) && ($_POST['title']!="") && isset($_POST['newstext']) && ($_POST['newstext']!=""))) fail("The title and/or the content of the news is empty.","news.php?post",true);
	
	$poster = $_SESSION['usr'];
	$ntitle = htmlfilter($_POST['title'],true);
	$ndate = gmt(U);
	$npost = htmlfilter($_POST['newstext'],true);

	mysql_query("INSERT INTO news (newscaster, title, date, newsart) VALUES ('$poster','$ntitle','$ndate','$npost')");
	header("Location: index.php");
?>