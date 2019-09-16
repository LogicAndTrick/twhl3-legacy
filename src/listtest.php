<?
	include 'middle.php';
	
	$res = mysql_query("SELECT * FROM entities WHERE entgame = '1' ORDER BY entname ASC");
	
	echo tri_column($res,"lol.php?lol=","entname","entID");
?>