<?
	$getenty = mysql_real_escape_string($_GET['id']);
	
	$entyq = mysql_query("SELECT * FROM wikientries LEFT JOIN wikititles ON entrytitle = titleID WHERE titleID = '$getenty' AND entryisactive = '1'");
	
	if (mysql_num_rows($entyq) == 0) fail("Entry not found.","wiki.php");
	
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
	<?=wiki_format($entr['entrycontent'])?>
</div>
<?
	$mod = false;
	if ($_SESSION['lvl'] >= 20) $mod = true;
	$comq = mysql_query("SELECT * FROM wikicomments LEFT JOIN users ON commentuser = userID WHERE commenttitle = '$getenty' ORDER BY commentdate ASC");
	$numcoms = mysql_num_rows($comq);
?>
<div class="single-center" id="gap-fix-bottom">
	<?=($numcoms > 0)?'<h1 class="no-bottom-border">Comments</h1>':'<h1>Comments</h1>'?>
	<div class="comments">
		<?
		if ($numcoms > 0) {
			$alt = "-alt";
			while ($comr = mysql_fetch_array($comq)) {
				if ($alt == "") $alt = "-alt";
				else $alt = "";
		?>
		<div class="comment-container<?=$alt?>">
			<span class="avatar"><img src="<?=getavatar($comr['commentuser'],$comr['avtype'],true)?>" alt="avatar" /></span>	
			<span class="name"><strong><a href="user.php?id=<?=$comr['commentuser']?>"><?=$comr['uid']?></a> says:</strong></span>
			<span class="date"><?=($mod || (isset($usr) && ($usr == $comr['commentuser'])))?'<a href="wiki.php?comment='.$comr['commentID'].'&amp;edit">Edit</a> | <a href="wiki.php?comment='.$comr['commentID'].'&amp;delete">Delete</a> | ':''?><?=(isset($usr))?'<a href="wikicommentreport.php?id='.$comr['commentID'].'">Report</a> | ':''?><?=timezone($comr['commentdate'],$_SESSION['tmz'],"jS F Y, H:i A")?></span>
			<div class="text"><?=comment_format($comr['commentcontent'])?></div>
		</div>
		<? } } else { ?><div class="sorry">There are no comments yet. Be the first!</div><? } ?>
		<div class="comment-box">
		<? if (isset($wiki_lvl) && ($wiki_lvl > 0)) { ?>
			<form action="wikiaddcomment.php?id=<?=$getenty?>" method="post">
				<fieldset>
					<textarea name="comment" rows="10" cols="76"></textarea>
					<input type="submit" value="Submit" />
				</fieldset>
			</form>
		<? } else { ?><div class="sorry">You are not able to comment. Are you logged in?</div><? } ?>
		</div>		
	</div>
</div>