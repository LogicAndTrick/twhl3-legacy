<?php

include 'functions.php';
include 'logins.php';

if (isset($_SESSION['lvl']) && ($_SESSION['lvl'] != "") && isset($_POST['shout']) && trim($_POST['shout'])!="" && trim($_POST['shout'])!="Type here" && trim($_POST['shout'])!="/me" && trim($_POST['shout'])!="/zap")
{
	//
	$sht = $_POST['shout'];
	if (strlen($sht)>200) $sht=substr($sht,0,200) . "...";
	$shoutval=htmlfilter($sht);
	$time=gmt(U); //date(H) . ":" . date(i);
	//
	$sql="INSERT INTO shouts (uid, shout, time) VALUES ('$usr','$shoutval','$time')";
	if (!mysql_query($sql,$dbh))
	{
		die('Error: ' . mysql_error());
	}
	$redir=$_POST['return'];
	header("Location: $redir");
}
else
header("Location: index.php");


	
?>