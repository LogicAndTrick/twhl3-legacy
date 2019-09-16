<?
	$getenty = mysql_real_escape_string($_GET['revert']);
	
	$entyq = mysql_query("SELECT * FROM wikientries LEFT JOIN wikititles ON entrytitle = titleID WHERE entryID = '$getenty' AND entryisactive = '0'");
	
	if (mysql_num_rows($entyq) == 0) fail("Entry not found, or the selected revision is already current.","wiki.php");
	
	mysql_query("UPDATE wikititles SET titlehits = titlehits+1 WHERE titleID = '$getenty'");
	
	$entr = mysql_fetch_array($entyq);
	$getsat = $entr['titlesubcat'];
	
	$satnaq = mysql_query("SELECT * FROM wikisubcats WHERE subcatID = '$getsat'");
	if (mysql_num_rows($satnaq) == 0) fail("Subcategory not found.","wiki.php");
	$satnar = mysql_fetch_array($satnaq);
	$subname = $satnar['subcatname'];
	$getcat = $satnar['subcatcat'];
	$satid = $satnar['subcatID'];
	
	$catnaq = mysql_query("SELECT * FROM wikicats WHERE catID = '$getcat'");
	if (mysql_num_rows($catnaq) == 0) fail("Category not found.","wiki.php");
	$catnar = mysql_fetch_array($catnaq);
	$catname = $catnar['catname'];
	$catid = $catnar['catID'];
?>
<div class="single-center">
	<h1><?=$entr['titletitle']?></h1>
	<h2><a href="wiki.php">Wiki</a> &gt; <a href="wiki.php?cat=<?=$catid?>"><?=$catname?></a> &gt; <a href="wiki.php?sub=<?=$satid?>"><?=$subname?></a> &gt; <?=$entr['titletitle']?> &gt; [<? if (isset($wiki_lvl) && ($wiki_lvl > 0)) { ?><a href="wiki.php?edit=<?=$entr['titleID']?>">Edit</a> | <? } ?><a href="wiki.php?history=<?=$entr['titleID']?>">History</a>]</h2>
	<form action="wikirevert.php?id=<?=$getenty?>" method="post">
		<fieldset class="new-thread">
			<p class="single-center-content-center">
				You can revert this entry to the revision shown below. Please confirm that you wish to do this.
			</p>
			<p class="single-center-content">
				<input class="right" type="text" size="30" name="details" value="Spam" />Enter the reason to revert:
			</p>
			<p class="single-center-content-center">
				<input type="submit" value="Revert" />
			</p>
		</fieldset>
	</form>
	<p class="single-center-content">
		<?=wiki_format($entr['entrycontent'])?>
	</p>
</div>