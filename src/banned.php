<?php

	include 'functions.php';

	$tl="";
$lout = false;
	
	require_once("db.inc.php");
	
	if (isset($_GET['id']) and $_GET['id']!="")
	{
		$id=mysql_real_escape_string($_GET['id']);
		$result=mysql_query("SELECT * FROM bans where userID='$id' ORDER BY banID LIMIT 1");
		if (mysql_num_rows($result) == 1)
		{
			$row = mysql_fetch_array($result);
			$bt=$row['time'];
			$reason=$row['reason'];
			$rstring="";
			if ($reason!="") $rstring="Reason: $reason";
			$left=$bt-gmt(U);
			if ($bt>1)
				$tl="$rstring<br />You are banned for another $left seconds. (About " . round($left/86400,3) . " days)";
if ($row['showlogout']>0) $lout = true;
		}
	}
	else
	{
		$ip=mysql_real_escape_string($_SERVER['REMOTE_ADDR']);
		$result=mysql_query("SELECT * FROM bans where IP='$ip' ORDER BY banID LIMIT 1");
		if (mysql_num_rows($result) == 1)
		{
			$row = mysql_fetch_array($result);
			$bt=$row['time'];
			$reason=$row['reason'];
			$rstring="";
			if ($reason!="") $rstring="Reason: $reason";
			$left=$bt-gmt(U);
			if ($bt>1)
				$tl="$rstring<br />You are banned for another $left seconds. (About " . round($left/86400,3) . " days)";
		}
	}
	mysql_close($dbh);
?>		
<html>
	<head>
		<title>TWHL doesn&#39t want you, and nor does your mother!</title>
	</head>
	<body style="background-color: #000000; text-align: center; color: #ffffff;">
		<p>
			<img src="images/banned.jpg" /><br />
			You have been Banned. 
			<?=$tl?>
		</p>
<?php
if ($lout) {
echo '<p><a href="logout.php">Click here to log out and create a new account</a></p>';
}
?>
	</body>
</html>
