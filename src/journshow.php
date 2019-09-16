<?
	
	$getjourn = mysql_real_escape_string($_GET['id']);
	$journalq = mysql_query("SELECT * FROM journals LEFT JOIN users ON ownerID = userID WHERE journalID = '$getjourn'");
	if (mysql_num_rows($journalq) == 0) fail("Journal not found.","journals.php");
	
	$canmod = false;
	if (isset($lvl) && ($lvl >= 35)) $canmod = true;
	
	$jrow = mysql_fetch_array($journalq);
	$avatar = getavatar($jrow['userID'],$jrow['avtype'],true);
?>
<div class="single-center">
	<h1 class="no-bottom-border">View Journal</h1>
	<div class="journals">
		<div class="journal-container">
			<span class="avatar"><img src="<?=$avatar?>" alt="avatar" /></span>
			<span class="name"><strong><a href="user.php?id=<?=$jrow['userID']?>"><?=$jrow['uid']?></a></strong></span>
			<span class="date"><?=($canmod || (isset($usr) && $usr != "" && $jrow['userID'] == $usr))?'<a href="journals.php?journal='.$jrow['journalID'].'&amp;edit">Edit</a> | <a href="journals.php?journal='.$jrow['journalID'].'&amp;delete">Delete</a> | ':''?><?=timezone($jrow['journaldate'],$_SESSION['tmz'],"jS F Y, H:i A")?></span>
			<div class="journal-text">
				<?=bio_format($jrow['journaltext'])?>
			</div>
		</div>
	</div>
</div>
<?
	$mod = false;
	if ($_SESSION['lvl'] >= 35) $mod = true;
	
	$lastposter = -1;
	$lastpostid = -1;
	$comeditq = mysql_query("SELECT * FROM journalcomments WHERE commjournal = '$getjourn' ORDER BY commdate DESC LIMIT 1");
	if (mysql_num_rows($comeditq) > 0)
	{
		$comeditr = mysql_fetch_array($comeditq);
		$lastposter = $comeditr['commuser'];
		$lastpostid = $comeditr['commID'];
	}
	$comq = mysql_query("SELECT * FROM journalcomments LEFT JOIN users ON commuser = userID WHERE commjournal = '$getjourn' ORDER BY commdate ASC");
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
			<span class="avatar"><img src="<?=getavatar($comr['commuser'],$comr['avtype'],true)?>" alt="avatar" /></span>	
			<span class="name"><strong><a href="user.php?id=<?=$comr['commuser']?>"><?=$comr['uid']?></a> says:</strong></span>
			<span class="date"><?=($mod || (($usr == $lastposter) && ($comr['commID'] == $lastpostid)))?'<a href="journals.php?comment='.$comr['commID'].'&amp;edit">Edit</a> | ':''?><?=($mod)?'<a href="journals.php?comment='.$comr['commID'].'&amp;delete">Delete</a> | ':''?><?=timezone($comr['commdate'],$_SESSION['tmz'],"jS F Y, H:i A")?></span>
			<div class="text"><?=comment_format($comr['commtext'])?></div>
		</div>
		<? } } else { ?><div class="sorry">There are no comments yet. Be the first!</div><? } ?>
		<div class="comment-box">
		<? if (isset($_SESSION['lvl'])) { ?>
			<form action="journaddcomment.php?id=<?=$getjourn?>" method="post">
				<fieldset>
					<textarea name="comment" rows="10" cols="76"></textarea>
					<input type="submit" value="Submit" />
				</fieldset>
			</form>
		<? } else { ?><div class="sorry">You must be logged in to comment.</div><? } ?>
		</div>		
	</div>
</div>