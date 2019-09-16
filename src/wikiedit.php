<?
	$getenty = mysql_real_escape_string($_GET['edit']);
	
	$entyq = mysql_query("SELECT * FROM wikientries LEFT JOIN wikititles ON entrytitle = titleID WHERE titleID = '$getenty' AND entryisactive = '1'");
	
	if (mysql_num_rows($entyq) == 0) fail("Entry not found.","wiki.php");
	
	if (!(isset($usr) && ($usr != "") && ($wiki_lvl > 0))) fail("You must be logged in, and have wiki access to do this.","index.php");
	
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
	<h2><a href="wiki.php">Wiki</a> &gt; <a href="wiki.php?cat=<?=$catid?>"><?=$catname?></a> &gt; <a href="wiki.php?sub=<?=$satid?>"><?=$subname?></a> &gt; <a href="wiki.php?id=<?=$entr['titleID']?>"><?=$entr['titletitle']?></a> &gt; [Edit | <a href="wiki.php?history=<?=$entr['titleID']?>">History</a>]</h2>
	<p class="single-center-content-center">
		<a href="wiki.php?request=<?=$getenty?>">Click here</a> to request this wiki entry be renamed or deleted.
	</p>
	<form action="wikichange.php?id=<?=$getenty?>" method="post">
		<fieldset class="new-thread">
			<div class="smilies" id="bb-toggle"><a href="javascript:toggle('bb')">[Show BBCode]</a></div>
			<div class="smilies" id="bb-content">
				<? include 'wikibbcode.php'; ?>
			</div>
			<fieldset style="text-align: center;">
				<textarea id="newposttext" rows="30" cols="76" name="content"><?=$entr['entrycontent']?></textarea>
			</fieldset>
			<p class="single-center-content">
				<input class="right" type="text" size="30" name="details" value="Minor Edit" />Enter a description of your edit:
			</p>
			<p class="single-center-content-center">
				<input type="button" value="Preview" onclick="javascript:ajax_wiki_preview()" />
			</p>
			<div id="wikiprevfade" class="fade-rollover">
				The preview will appear under this box.
			</div>
			<p class="single-center-content-center">
				<input type="submit" value="Edit" />
			</p>
		</fieldset>
	</form>
</div>
<div class="single-center" id="wikiprevdiv" style="display: none; margin-top: 0;">
	<h1><?=$entr['titletitle']?></h1>
	<h2><a href="wiki.php">Wiki</a> &gt; <a href="wiki.php?cat=<?=$catid?>"><?=$catname?></a> &gt; <a href="wiki.php?sub=<?=$satid?>"><?=$subname?></a> &gt; <?=$entr['titletitle']?></h2>
	<p class="single-center-content" id="wikiprevcont">
		Loading...
	</p>
</div>