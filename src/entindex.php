<div class="single-center">
	<h1>The TWHL Entity Guides</h1>
	<p class="single-center-content">
		Welcome to the TWHL Entity Guides! Here you'll find a comprehensive guide to just about every entity avaliable for use in Half-Life, Counter-Strike and others. They're a great reference point for anyone who's stuck on an entity setup, so be sure to check it out.
	</p>	
	<p class="single-center-content">
		Found a mistake in one of the guides? Want to add some content? <a href="#">Contact us</a>.
	</p>	
</div>
<div class="single-center" id="gap-fix">
<?
	$entq = mysql_query("SELECT entgameID,entmapgame,entgamedesc,entgametext,gamename,shortname,engine,gameorder,enginename FROM entitygames LEFT JOIN mapgames ON entmapgame = gameID LEFT JOIN mapengines ON engine = engineID WHERE entgameopen = '1' ORDER BY engine ASC, gameorder ASC");
	if (mysql_num_rows($entq) > 0)
	{
?>
	<h1 class="no-bottom-border">The Guides</h1>
<?
		while ($entr = mysql_fetch_array($entq))
		{
?>
	<h2 class="top-border">The <?=$entr['gamename']?> Entity Guide</h2>
	<p class="single-center-content" id="center">
		<a href="entity.php?game=<?=$entr['entgameID']?>"><img src="http://twhl.info/gfx/award_forum.gif" alt="<?=$entr['shortname']?> entity guide" /></a>

	</p>
	<p class="single-center-content">
		<?=$entr['entgamedesc']?>
	</p>
<?
		}
	}
	else
	{
?>
	<h1>The Guides</h1>
	<p class="single-center-content">
		There are no guides available at the moment. Please check back later.
	</p>
<?
	}
?>
</div>