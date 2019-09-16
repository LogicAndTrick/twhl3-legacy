<?php

include 'functions.php';
include 'logins.php';

$shout_text = urldecode($_POST['shout']);

if (isset($_SESSION['lvl']) && ($_SESSION['lvl'] != "") &&
isset($shout_text) && trim($shout_text)!="" && trim($shout_text)!="Type here" && trim($shout_text)!="/me" && trim($shout_text)!="/zap")
{
	$sht = $shout_text;
	if (strlen($sht)>200) $sht=substr($sht,0,200) . "...";
	$shoutval=htmlfilter($sht);
	$time=gmt(U);
	mysql_query("INSERT INTO shouts (uid, shout, time) VALUES ('$usr','$shoutval','$time')");
}


	
?>