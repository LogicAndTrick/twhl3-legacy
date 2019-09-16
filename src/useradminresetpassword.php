<?
	include 'middle.php';	
	
	if (!(isset($usr) && ($usr != ""))) fail("You must be logged in to view this page.","index.php",true);
	if (!(isset($lvl) && ($lvl >= 40))) fail("You must be an admin to view this page! Get the hell out!","index.php",true);
	
	if (!(isset($_POST['user_id']) && $_POST['user_id'] != "")) fail("No user specified.","index.php",true);
	$user_id = mysql_real_escape_string($_POST['user_id']);
	$peditq = mysql_query("SELECT * FROM users WHERE userID = '$user_id'");
	if (mysql_num_rows($peditq) == 0) fail("This user doesn't exist.","index.php",true);
	
	if (!((isset($_POST['resetpass1']) && $_POST['resetpass1'] != "") || (isset($_POST['resetpass2']) && $_POST['resetpass2'] != "")))
		fail("Password can't be blank. The password was not reset.","user.php?manage=$user_id&amp;pass",true);
		
	$pass1 = strtolower(trim($_POST['resetpass1']));
	$pass2 = strtolower(trim($_POST['resetpass2']));		
	if (strlen($pass1) > 20) $pass1 = substr($pass1,0,20);
	if (strlen($pass2) > 20) $pass2 = substr($pass2,0,20);
	$pass1 = md5($pass1);
	$pass2 = md5($pass2);
	if ($pass1 != $pass2) fail("The passwords didn't match. The password was not reset.","user.php?manage=$user_id&amp;pass",true);
	
	mysql_query("UPDATE users SET pwd = '$pass1' WHERE userID = '$user_id' LIMIT 1");

	header("Location: user.php?manage=$user_id&profile");
?>