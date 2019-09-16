<?
	$getent = mysql_real_escape_string($_GET['id']);
	
	$entq = mysql_query("SELECT * FROM entities LEFT JOIN entitygames ON entgame = entgameID LEFT JOIN mapgames ON entmapgame = gameID LEFT JOIN entitytypes ON entcat = entcatID WHERE entID = '$getent'");
	
	if (mysql_num_rows($entq) == 0) fail("Entity not found.","entity.php");
	
		$entr = mysql_fetch_array($entq);
?>
<div class="single-center">
	<h1><?=$entr['entname']?></h1>
	<h2><a href="entity.php?game=<?=$entr['entgame']?>"><?=$entr['shortname']?> Entity Guide</a> | <a href="entity.php?game=<?=$entr['entgame']?>&amp;type=<?=$entr['entcat']?>"><?=$entr['enttype']?></a></h2>
	<p class="single-center-content">
		<?=$entr['enttext']?>
	</p>
	<h3>Attributes</h3>
	<ul>
<?
		$attq = mysql_query("SELECT * FROM entlinks LEFT JOIN glossary ON link = glossID WHERE linktype = 2 AND linkent = '$getent' ORDER BY linkID ASC");
		while ($attr = mysql_fetch_array($attq))
		{
?>
	
		<li><a href="glossary.php?id=<?=$attr['glossID']?>"><?=$attr['glossname']?></a> (<?=$attr['glossaltname']?>) - <?=$attr['glosstext']?></li>
<?
		}
		$proplist = explode(",,",$entr['entprops']);
		foreach ($proplist as $prp)
		{
			$prp = trim($prp);
			$prp = str_ireplace("&lt;X&gt;","",$prp);
			$prp = str_ireplace("&lt;/X&gt;"," ",$prp);
			$prp = str_ireplace("&lt;Y&gt;","(",$prp);
			$prp = str_ireplace("&lt;/Y&gt;",")",$prp);
?>
		<li><?=text_bbcode_only($prp)?></li>
<?
		}
?>
	</ul>
	<h3>Flags</h3>
	<ul>
<?
		$flgq = mysql_query("SELECT * FROM entlinks LEFT JOIN glossary ON link = glossID WHERE linktype = 1 AND linkent = '$getent' ORDER BY linkID ASC");
		while ($flgr = mysql_fetch_array($flgq))
		{
?>
	
		<li><a href="glossary.php?id=<?=$flgr['glossID']?>"><?=$flgr['glossname']?></a> (<?=$flgr['glossaltname']?>) - <?=$flgr['glosstext']?></li>
<?
		}
		$flaglist = explode(",,",$entr['entflags']);
		foreach ($flaglist as $flg)
		{
			$flg = trim($flg);
			$flg = str_ireplace("&lt;X&gt;","",$flg);
			$flg = str_ireplace("&lt;/X&gt;"," ",$flg);
			$flg = str_ireplace("&lt;Y&gt;","(",$flg);
			$flg = str_ireplace("&lt;/Y&gt;",")",$flg);
?>
		<li><?=text_bbcode_only($flg)?></li>
<?
		}
?>
	</ul>
<? 
		if (trim($entr['entnotes']) != "")
		{
			$nota = explode(",,",$entr['entnotes']);
?>
	<h3>Notes</h3>
	<ul>
<?
			foreach ($nota as $note)
			{
?>
	<li><?=trim($note)?></li>
<?
			}
?>
	</ul>
<?
		}
		
		$example = (trim($entr['entexample']) != "")?$entr['entexample']:false;
		
		$entq = mysql_query("SELECT * FROM entlinks LEFT JOIN entities ON link = entID WHERE linktype = 6 AND linkent = '$getent' ORDER BY entname ASC");
		$relent = tri_column($entq,"entity.php?id=","entname","entID");
		
		$tutq = mysql_query("SELECT * FROM entlinks LEFT JOIN tutorials ON link = tutorialID WHERE linktype = 3 AND linkent = '$getent' ORDER BY name ASC");
		$reltut = tri_column($tutq,"tutorial.php?id=","name","tutorialID");
		
		if ($example || $relent || $reltut)
		{
?>
	<h2 class="top-border">Related Info</h2>
<?
			if ($example)
			{
?>
	<h3>Example Map(s)</h3>
	<p class="single-center-content">
		<?=$example?>
	</p>
<?
			}
			if ($relent)
			{
?>
	
	<h3>Related Entities</h3>
	<?=$relent?>
<?
			}
			if ($reltut)
			{
?>
	<h3>Related Tutorials</h3>			
		<?=$reltut?>
<?
			}
		}
?>
</div>