<?
	$getgame = mysql_real_escape_string($_GET['game']);
	$gettype = mysql_real_escape_string($_GET['type']);
	
	if (is_numeric($gettype)) $catcond = "= $gettype";
	else $catcond = "> 0";
	
	$allowed = false;
	
	$cntent = mysql_query("SELECT entgame, entgameopen, gamename, count(*) AS cnt, entgametext FROM entities LEFT JOIN entitygames ON entgame = entgameID LEFT JOIN mapgames ON entmapgame = gameID GROUP BY entgame HAVING entgame = '$getgame' AND (entgameopen = '1' OR '$lvl' >= 25)");
	if (mysql_num_rows($cntent) > 0)
	{
		$entcntr = mysql_fetch_array($cntent);
		$gname = $entcntr['gamename'];
		$numents = $entcntr['cnt'];
		if ($numents > 0) $allowed = true;
	}
	
	if ($allowed)
	{
		$entq = mysql_query("SELECT * FROM entities WHERE entgame = '$getgame' AND entcat $catcond ORDER BY entname ASC");
		if (mysql_num_rows($entq) == 0) $allowed = false;
		
		$catnm = "All";
		if (is_numeric($gettype) && $allowed)
		{
			$catnmq = mysql_query("SELECT * FROM entitytypes WHERE entcatID = '$gettype' LIMIT 1");
			if (mysql_num_rows($catnmq) > 0)
			{
				$catnmr = mysql_fetch_array($catnmq);
				$catnm = $catnmr['enttype'];
			}
		}
	}
	
	if (!$allowed) fail("Category not found, or no entities exist.","entity.php");
?>
<div class="single-center">
	<h1><?=$gname?> Entities</h1>
	<h2><a href="entity.php">Entity Guides</a> &gt; <a href="entity.php?game=<?=$getgame?>"><?=$gname?></a> &gt; <?=$catnm?> Entities</h2>
<?
		echo tri_column($entq,"entity.php?id=","entname","entID");
?>
</div>