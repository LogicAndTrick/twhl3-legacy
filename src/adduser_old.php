<?php

	include 'middle.php';
	
	if (!(isset($_POST['username']) && $_POST['username'] != "")) fail("Invalid username.","register.php",true);
	$username = $_POST['username'];
	$username = str_replace("\n", "", $username);
	$username = str_replace("\r", "", $username);
	$username = htmlfilter($username);
	
	$usrnmq = mysql_query("SELECT * FROM users WHERE uid = '$username'");
	if (mysql_num_rows($usrnmq) > 0) fail ("That username already exists.","register.php",true);
	
	$badchars = array("/","\\",'"',"'","<",">",":","*","|","?","&","\t");
	for($i=0;$i<count($badchars);$i++) {
	  if (!strpos($uname,$badchars[$i]) === false) {
	    fail("The following characters are not allowed in your username: /, \\. \", ', <, >, :, *, |, ?, &","register.php",true);
	  }
	}
	
	if (!((isset($_POST['pass1']) && $_POST['pass1'] != "") || (isset($_POST['pass2']) && $_POST['pass2'] != ""))) fail("One or both of your passwords were blank.","register.php",true);
	$pass1 = strtolower(trim($_POST['pass1']));
	$pass2 = strtolower(trim($_POST['pass2']));		
	if (strlen($pass1) > 20) $pass1 = substr($pass1,0,20);
	if (strlen($pass2) > 20) $pass2 = substr($pass2,0,20);
	$pass1 = md5($pass1);
	$pass2 = md5($pass2);
	if ($pass1 != $pass2) fail("The passwords didn't match.","register.php",true);
	
	$email = htmlfilter($_POST['email']);
	if (!isset($email) || $email == "" || strpos($email,"@") === false || strpos($email,".") === false)
		fail("Your email is invalid.","register.php",true);
	
	$timezone = htmlfilter($_POST['timezone']);
	if (!isset($timezone) || !is_numeric($timezone) || $timezone < -12 || $timezone > 12)
		fail("Your timezone is invalid.","register.php",true);
	
	$showemail = 0;
	if ($_POST['showemail']) $showemail = 1;
	
	$code=strtoupper($_POST['capt']);
	if($_SESSION['security_code'] == $code && !empty($_SESSION['security_code'])) unset($_SESSION['security_code']);
	else  fail("The code you entered was incorrect.","register.php",true);
	
	$thenow = gmt("U");
	$ipadd = $_SERVER['REMOTE_ADDR'];
	
	mysql_query("INSERT INTO users (uid,pwd,log,lvl,joined,ipadd,email,allowemail,timezone) VALUES ('$username','$pass1','0','0','$thenow','$ipadd','$email','$showemail','$timezone')");

	header("Location: register.php?complete&user=$username");
	
?>