<?
	if (isset($_GET['user'])) include 'journuser.php';
	elseif (isset($_GET['id'])) include 'journshow.php';
	elseif (isset($_GET['journal'])) include 'journmod.php';
	elseif (isset($_GET['comment'])) include 'journmodcomment.php';
	else include 'journrecent.php';
?>