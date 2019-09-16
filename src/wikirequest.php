<?
	$getenty = mysql_real_escape_string($_GET['request']);
	
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
	<h2><a href="wiki.php">Wiki</a> &gt; <a href="wiki.php?cat=<?=$catid?>"><?=$catname?></a> &gt; <a href="wiki.php?sub=<?=$satid?>"><?=$subname?></a> &gt; <a href="wiki.php?id=<?=$entr['titleID']?>"><?=$entr['titletitle']?></a> &gt; Request</h2>
	<p class="single-center-content">
		Here you can request to have this entry renamed or deleted by a moderator. Regular users can't do this because it opens more options for abuse of the system and spam.
	</p>
	<form action="wikirequestsubmit.php?id=<?=$getenty?>" method="post">
		<fieldset class="new-thread">
			<p class="single-center-content-center">
				<input type="radio" name="choose" value="1" checked="checked" onclick="javascript:tabswitcher(new Array('ren-radio','del-radio'))" /> Rename Entry<br />
				<input type="radio" name="choose" value="2" onclick="javascript:tabswitcher(new Array('del-radio','ren-radio'))" /> Delete Entry
			</p>
			<div id="ren-radio" style="margin: 12px;">
				<input class="right" type="text" size="30" name="newtitle" value="<?=$entr['titletitle']?>" />Enter the new title:
			</div>
			<div id="del-radio" style="margin: 12px; display: none;">
				<input class="right" type="text" size="30" name="delreas" value="" />Enter a reason to delete:
			</div>
			<p class="single-center-content-center">
				<input type="submit" value="Submit Request" />
			</p>
		</fieldset>
	</form>
</div>