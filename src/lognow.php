<?php

	//login and other user stuffs
	include 'functions.php';
	include 'logins.php';
	//content
	if (isset($_POST['return']) && ($_POST['return'] != ""))
		header("Location: " . $_POST['return']); 
	else
		header("Location: index.php"); 
?>