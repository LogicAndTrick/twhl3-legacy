<?
	include 'middle.php';
	//get pic id
	$getpic = mysql_real_escape_string($_GET['id']);
	//get tutorial
	$picq = mysql_query("SELECT * FROM tutorialpics WHERE picID = '$getpic'");
	if (mysql_num_rows($picq) == 0) fail("Image not found.","tutorial.php",true);
	$picr = mysql_fetch_array($picq);
	$tutid = $picr['tutID'];
	$file = $picr['piclink'];
	//get owner
	$tutq = mysql_query("SELECT * FROM tutorials WHERE tutorialID = '$tutid'");
	if (mysql_num_rows($tutq) == 0) fail("Tutorial not found.","tutorial.php",true);
	$tutr = mysql_fetch_array($tutq);
	$authorid = $tutr['authorid'];
	//check if allowed to delete
	if (!(isset($usr) && (($usr == $authorid) || ($lvl >= 25)))) fail("You are not logged on, or you do not have permission to do this.","tutorial.php",true);
	//delete image
	@unlink("tutpics/$file");
	mysql_query("DELETE FROM tutorialpics WHERE picID = '$getpic'");
	//redirect
	$redirpage = mysql_real_escape_string($_POST['page']);
	if ($redirpage < 0 || $redirpage > 5 || !is_numeric($redirpage) || !isset($redirpage)) $redirpage = 1;
	header("Location: tutorial.php?edit=$tutid&page=$redirpage");
?>