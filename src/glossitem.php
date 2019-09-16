<?
	$getgloss = mysql_real_escape_string($_GET['id']);
	$glossq = mysql_query("SELECT * FROM glossary LEFT JOIN glossarycats ON glosscat = glosscatID LEFT JOIN mapgames ON glossgame = gameID WHERE glossID = '$getgloss'");
	
	if (mysql_num_rows($glossq) == 0) fail("Category not found.","glossary.php");
	
		$glr = mysql_fetch_array($glossq);
?>

<div class="single-center">
	<h1><?=$glr['glossname']?><?=(trim($glr['glossaltname'])!="")?' ('.$glr['glossaltname'].')':''?></h1>
	<h2><a href="#"><?=$glr['shortname']?> Glossary</a> | <a href="glossary.php?cat=<?=$glr['glosscat']?>"><?=$glr['glosscatname']?></a></h2>

	<p class="single-center-content">
		<?=$glr['glosstext']?>
	</p>
<?
	$relglo = mysql_query("SELECT * FROM glosslinks LEFT JOIN glossary ON link = glossID WHERE linkentry = '$getgloss' ORDER BY glossname ASC");
	$linkgl = tri_column($relglo,"glossary.php?id=","glossname","glossID");
	
	$linq = mysql_query("SELECT * FROM entlinks LEFT JOIN entities ON linkent = entID WHERE (linktype = 2 OR linktype = 1 OR linktype = 4 OR linktype = 5) AND link = '$getgloss' ORDER BY entname ASC");
	$linked = tri_column($linq,"entity.php?id=","entname","entID");
	
		if ($linkgl || $linked)
		{
?>
	<h2 class="top-border">Related Info</h2>
<?
			if ($linkgl)
			{
?>
	<h3>Related Entries</h3>
	<?=$linkgl?>
<?
			}
			if ($linked)
			{
?>
	<h3>Entities that Link to this Entry</h3>
	<?=$linked?>
<?
			}
		}
?>
</div>