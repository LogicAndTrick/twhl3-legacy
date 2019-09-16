<?
	include 'middle.php';	
	
	if (!(isset($usr) && ($usr != ""))) fail("You must be logged in to view this page.","index.php",true);
	if (!(isset($lvl) && ($lvl >= 40))) fail("You must be an admin to view this page! Get the hell out!","index.php",true);
	
	if (!(isset($_POST['user_id']) && $_POST['user_id'] != "")) fail("No user specified.","index.php",true);
	$user_id = mysql_real_escape_string($_POST['user_id']);
	$peditq = mysql_query("SELECT * FROM users WHERE userID = '$user_id'");
	if (mysql_num_rows($peditq) == 0) fail("This user doesn't exist.","index.php",true);
	
	$awardtype = $_POST['award'];
	if (!is_numeric($awardtype)) fail("This award type is not valid.","user.php?manage=$user_id&awards",true);
	
	$awardreason = '';
	$awarddate = gmt("M Y");
	
	mysql_query("INSERT INTO awards (awarduser,awardtype,awardreason,awardwhen) VALUES ('$user_id','$awardtype','$awardreason','$awarddate')");
	
	header("Location: user.php?manage=$user_id&awards");
?>