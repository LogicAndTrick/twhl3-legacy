<?
	if (isset($_GET['id'])) include 'motmreviews.php';
	elseif (isset($_GET['comment'])) include 'motmmodcomment.php';
	elseif (isset($_GET['admin']) && isset($lvl) && ($lvl >= 30)) include 'motmadmin.php';
	else include 'motmlist.php';
?>