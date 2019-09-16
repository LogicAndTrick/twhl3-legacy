<?
	$getenty = mysql_real_escape_string($_GET['history']);
	
	$titleq = mysql_query("SELECT * FROM wikititles WHERE titleID = '$getenty'");
	if (mysql_num_rows($titleq) == 0) fail("Entry not found.","wiki.php");
	$entyq = mysql_query("SELECT * FROM wikientries LEFT JOIN wikititles ON entrytitle = titleID LEFT JOIN users ON entryuser = userID WHERE titleID = '$getenty' ORDER BY entryrevision DESC");
	if (mysql_num_rows($entyq) == 0) fail("Entry not found.","wiki.php");
	
	$entr = mysql_fetch_array($titleq);
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
	
	$currentrev = -1;
?>
<div class="single-center">
	<h1>History:- <?=$entr['titletitle']?></h1>
	<h2><a href="wiki.php">Wiki</a> &gt; <a href="wiki.php?cat=<?=$catid?>"><?=$catname?></a> &gt; <a href="wiki.php?sub=<?=$satid?>"><?=$subname?></a> &gt; <a href="wiki.php?id=<?=$entr['titleID']?>"><?=$entr['titletitle']?></a> &gt; [<? if (isset($wiki_lvl) && ($wiki_lvl > 0)) { ?><a href="wiki.php?edit=<?=$entr['titleID']?>">Edit</a> | <? } ?>History]</h2>
	<form action="wikichange.php?id=<?=$getenty?>" name="wikicompfrm" method="post">
		<table style="width: 667px; border: 0; margin: 5px;">
			<fieldset>
				<tr>
					<th>Old</th>
					<th>New</th>
					<th>&nbsp;</th>
				</tr>
<?
	while ($entrow = mysql_fetch_array($entyq))
	{
		if ($currentrev < 0) $currentrev = $entrow['entryrevision'];
?>
				<tr>
					<td style="text-align: center; padding: 5px;  width: 30px;"><input type="radio" name="compold" value="<?=$entrow['entryID']?>" <?=($entrow['entryrevision']==($currentrev-1))?'checked="checked" ':''?>/></td>
					<td style="text-align: center; padding: 5px;  width: 30px;"><input type="radio" name="compnew" value="<?=$entrow['entryID']?>" <?=($entrow['entryrevision']==$currentrev)?'checked="checked" ':''?>/></td>
					<td style="text-align: left; padding: 5px;">Revision #<?=$entrow['entryrevision']?> - <em><?=$entrow['entrydetails']?></em> - <a href="user.php?id=<?=$entrow['userID']?>"><?=$entrow['uid']?></a>, <?=timezone($entrow['entrydate'],$_SESSION['tmz'],"d M, y, h:ia")?> (<?=($entrow['entryrevision']!=$currentrev)?'<a href="wiki.php?revert='.$entrow['entryID'].'">revert</a>':'current'?>)</td>
				</tr>
<?
	}
?>
			</table>
			<div id="wikicompfade" class="fade-rollover">
				The preview will appear under this box.
			</div>
			<p class="single-center-content-center">
				<input type="button" value="Compare" onclick="javascript:ajax_wiki_compare()" />
			</p>
		</fieldset>
	</form>
</div>
<div class="single-center" id="wikicompdiv" style="display: none; margin-top: 0;">
	<h1><?=$entr['titletitle']?> - Comparison</h1>
	<div id="wikicompcont">
		<p class="single-center-content">
			Loading...
		</p>
	</div>
</div>