<div class="single-center">
	<h1>Wiki</h1>
	<h2>Wiki &gt; <a href="wiki.php?log=1">Changelog</a><? if (isset($lvl) && ($lvl >= 20)) { ?> &gt; <a href="wiki.php?admin">Admin</a><? } ?></h2>
	<p class="single-center-content">
		Welcome to the TWHL Wiki! Choose a category below.
	</p>
	<p class="single-center-content">
		Wiki Sections:<br />
		<br />
<?
	$catq = mysql_query("SELECT * FROM wikicats WHERE catopen = 1 ORDER BY catID ASC");
	while($catr = mysql_fetch_array($catq))
	{
?>
		<a href="wiki.php?cat=<?=$catr['catID']?>"><?=$catr['catname']?></a> - <?=$catr['catdesc']?><br /><br />
<?
	}
?>
	</p>
</div>