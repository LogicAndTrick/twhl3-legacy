<?php	

	$getgame = mysql_real_escape_string($_GET['game']);
	
	$allowed = false;
	
	$cntent = mysql_query("SELECT entgame, entgameopen, gamename, count(*) AS cnt, entgametext FROM entities LEFT JOIN entitygames ON entgame = entgameID LEFT JOIN mapgames ON entmapgame = gameID GROUP BY entgame HAVING entgame = '$getgame' AND (entgameopen = '1' OR '$lvl' >= 25)");
	if (mysql_num_rows($cntent) > 0)
	{
		$entcntr = mysql_fetch_array($cntent);
		$desc = $entcntr['entgametext'];
		$gname = $entcntr['gamename'];
		$numents = $entcntr['cnt'];
		if ($numents > 0) $allowed = true;
	}
	
	if ($allowed)
	{
		$entq = mysql_query("SELECT entcat,count(entcat) AS cnt,enttype FROM entities LEFT JOIN entitytypes ON entcat = entcatID WHERE entgame = '$getgame' GROUP BY entcat ORDER BY enttype ASC");
		if (mysql_num_rows($entq) == 0) $allowed = false;
	}
	
	if (!$allowed) fail("Category not found","entity.php");
?>
<div class="single-center">
	<h1><?=$gname?> Entity Guide</h1>
	<?=$desc?>
</div>	

<div class="single-center" id="gap-fix">
	<h1>Categories</h1>
	<p class="single-center-content">
		Can't find the Entity you're looking for? Use our Search function at the top of the page! If you've found an incorrect entry, or would like to add to this guide, please contact one of the <a href="#">Admins</a>.
	</p>	
	<p class="single-center-content">	
		<a href="entity.php?game=<?=$getgame?>&amp;type=all">All</a> (<?=$numents?>)
	</p>	
	<p class="single-center-content">
<?
		while ($entrow = mysql_fetch_array($entq))
		{
?>
		<a href="entity.php?game=<?=$getgame?>&amp;type=<?=$entrow['entcat']?>"><?=$entrow['enttype']?></a> (<?=$entrow['cnt']?>) <br />
<?
		}
?>
	</p>
</div>