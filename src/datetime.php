<?php
	require_once("db.inc.php");
	include 'functions.php';
	echo getztime(0);
	echo "<br>";
	echo date("U");
	echo "<br>";
	echo date("jS F Y, H:i A");
	echo "<br>";
	echo gmt();
	echo "<br>";
echo date("jS F Y, H:i A",1236003619) . "<br>";
echo gmdate("jS F Y, H:i A",1236003619) . "<br>";
?>