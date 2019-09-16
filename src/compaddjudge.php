<?php
	include 'middle.php';
	
	$getcomp = mysql_real_escape_string($_GET['id']);
	$compq = mysql_query("SELECT * FROM compos WHERE compID = '$getcomp'");
	
	if (!(isset($lvl) && ($lvl >= 40))) fail("You are not logged in or you don't have permission to do this.","competitions.php?hub=$getcomp",true);
	if (mysql_num_rows($compq) == 0) fail("Competition not found.","competitions.php?hub=$getcomp",true);
	
	$newjudge = 0;
	if (isset($_POST['choose']) && $_POST['choose'] == 'recent')
	{
		$newjudge = $_POST['rec'];
	}
	elseif (isset($_POST['choose']) && $_POST['choose'] == 'admins')
	{
		$newjudge = $_POST['adm'];
	}
	elseif (isset($_POST['choose']) && $_POST['choose'] == 'userid')
	{
		$newjudge = $_POST['use'];
	}
	
	if (!isset($newjudge) || !is_numeric($newjudge) || $newjudge <= 0) $newjudge = 0;

    $newjudge = mysql_real_escape_string($newjudge);
	
	$judgeq = mysql_query("SELECT * FROM compjudges WHERE judgecomp = '$getcomp' AND judgeuser = '$newjudge'");
	
	if ($newjudge > 0 && mysql_num_rows($judgeq) == 0)
	{
		mysql_query("INSERT INTO compjudges (judgecomp,judgeuser) VALUES ('$getcomp','$newjudge')");
	}
	header("Location: competitions.php?hub=$getcomp&judges");


?>