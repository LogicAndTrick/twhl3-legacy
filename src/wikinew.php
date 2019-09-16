<?
	$getsat = mysql_real_escape_string($_GET['new']);
	
	if (!(isset($usr) && ($usr != "") && ($wiki_lvl > 0))) fail("You must be logged in, and have wiki access to do this.","index.php");
	
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
	<h1>Add Entry - <?=$subname?></h1>
	<h2><a href="wiki.php">Wiki</a> &gt; <a href="wiki.php?cat=<?=$catid?>"><?=$catname?></a> &gt; <a href="wiki.php?sub=<?=$satid?>"><?=$subname?></a> &gt; New Entry</h2>
	<form action="wikinewentry.php?id=<?=$getsat?>" method="post">
		<fieldset class="new-thread">
			<div class="smilies" id="bb-toggle"><a href="javascript:toggle('bb')">[Show BBCode]</a></div>
			<div class="smilies" id="bb-content">
				<? include 'wikibbcode.php'; ?>
			</div>
			<p class="single-center-content">
				<input class="right" type="text" size="30" name="entname" id="wikinewname" value="" />Entry Name:
			</p>
			<fieldset style="text-align: center;">
				<textarea id="newposttext" rows="30" cols="76" name="content"></textarea>
			</fieldset>
			<p class="single-center-content-center">
				<input type="button" value="Preview" onclick="javascript:ajax_wiki_newpreview()" />
			</p>
			<div id="wikiprevfade" class="fade-rollover">
				The preview will appear under this box.
			</div>
			<p class="single-center-content-center">
				<input type="submit" value="Create Entry" />
			</p>
		</fieldset>
	</form>
</div>
<div class="single-center" id="wikiprevdiv" style="display: none; margin-top: 0;">
	<h1>Add Entry - <?=$subname?></h1>
	<h2><a href="wiki.php">Wiki</a> &gt; <a href="wiki.php?cat=<?=$catid?>"><?=$catname?></a> &gt; <a href="wiki.php?sub=<?=$satid?>"><?=$subname?></a> &gt; <span id="wikiprevname">Loading...</span></h2>
	<p class="single-center-content" id="wikiprevcont">
		Loading...
	</p>
</div>