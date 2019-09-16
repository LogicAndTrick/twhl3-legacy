<?
	include 'top.php';
	if (isset($usr) && ($usr != ""))
		include 'privview.php';
	else fail("You must be logged in to do this.","index.php");
	include 'bottom.php';
?>