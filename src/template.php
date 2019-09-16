<?php
	//login and other user stuffs
	include 'functions.php';
	include 'logins.php';
	//content
	include 'header.php';
	include 'sidebar.php';
	
	echo "<!--CONTENT HERE-->";
	echo mysql_real_escape_string($_SERVER['REMOTE_ADDR']).'<br>';
	
	$bansrch=mysql_real_escape_string($_SERVER['REMOTE_ADDR']);
	$banq = mysql_query("SELECT * FROM bans WHERE IP = '$bansrch' ORDER BY banID DESC LIMIT 1");
	$banned = false;
	if (mysql_num_rows($banq) > 0)
	{
		$banr = mysql_fetch_array($banq);
		$time = $banr['time'];
		if ($time < 0) $banned = true;
		elseif ($time > gmt("U")) $banned = true;
		echo $time;
	}
	
	include 'footer.php';
?>