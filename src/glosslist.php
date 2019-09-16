<?
	$getcat = mysql_real_escape_string($_GET['cat']);
	if (!isset($getcat) || $getcat == 0 || $getcat == "") $getcat = 3;
	
	$allowed = false;
	
	$glossq = mysql_query("SELECT * FROM glossary WHERE glosscat = '$getcat' ORDER BY glossname ASC");
	$glosscatq = mysql_query("SELECT * FROM glossarycats WHERE glosscatID = '$getcat'");
	
	if ((mysql_num_rows($glosscatq) > 0) && (mysql_num_rows($glossq) > 0))
	{
		$gcr = mysql_fetch_array($glosscatq);
		$gcname = $gcr['glosscatname'];
		$allowed = true;
	}
	
	if (!$allowed) fail("Category not found, or no entities exist.","glossary.php");
?>
<div class="single-center">
	<h1>Glossary</h1>
	<h2><a href="glossary.php">Glossary</a> &gt; <?=$gcname?></h2>
<?
		echo tri_column($glossq,"glossary.php?id=","glossname","glossID");
?>
</div>