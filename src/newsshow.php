<?
	$getnews=mysql_real_escape_string($_GET['id']);
	$newsq = mysql_query("SELECT newsID,userID,uid,lvl,news.title,date,newsart,closed,avtype,levelname,allowtitle,usetitle,users.title AS usertitle FROM news LEFT JOIN users ON newscaster = userID LEFT JOIN userlevels ON levelnum = lvl WHERE newsID = '$getnews' LIMIT 1") or die(mysql_error());
	if (mysql_num_rows($newsq) == 0) fail("News not found.","news.php");
	
	$newr = mysql_fetch_array($newsq);
	$nid=$newr['newsID'];
	$postrid=$newr['userID'];
	$postr=$newr['uid'];
	$postrlvl=$newr['lvl'];
	$title=$newr['title'];
	$comm_closed=$newr['closed'];
	$pdate=timezone($newr['date'],$_SESSION['tmz'],"F jS, Y");
	$messg=news_format($newr['newsart']);
	$avtype=$newr['avtype'];
	
	$userlvlname=$newr['levelname'];
	if (($newr['allowtitle'] > 0 || $newr['lvl'] >= 20) && $newr['usetitle'] > 0 && $newr['usertitle'] != "") $userlvlname = $newr['usertitle'];
	
	$avatar=getresizedavatar($postrid,$avtype,100);
?>
<div class="single-center">	
	<h1 class="no-bottom-border">View News Post</h1>
	<span class="date">Posted <?=$pdate?></span>
	<h2 class="news-archive"><?=$title?></h2>
	<span class="news-info">
		<img src="<?=$avatar?>" alt="<?=$postr?>" /><br />
		<a href="user.php?id=<?=$postrid?>"><?=$postr?></a><br />
		<?=$userlvlname?><br />
		<? if (isset($lvl) && $lvl >=35) { ?><a href="news.php?edit=<?=$nid?>">Edit</a>/<a href="news.php?delete=<?=$nid?>">Delete</a><? } ?>
	</span>
	<p class="news-content">
		<?=$messg?>
	</p>
</div>
<?
	$mod = false;
	if ($_SESSION['lvl'] >= 35) $mod = true;
	
	$lastposter = -1;
	$lastpostid = -1;
	$comeditq = mysql_query("SELECT * FROM newscomments WHERE commnews = '$getnews' ORDER BY commdate DESC LIMIT 1");
	if (mysql_num_rows($comeditq) > 0)
	{
		$comeditr = mysql_fetch_array($comeditq);
		$lastposter = $comeditr['commuser'];
		$lastpostid = $comeditr['commID'];
	}
	$comq = mysql_query("SELECT * FROM newscomments LEFT JOIN users ON commuser = userID WHERE commnews = '$getnews' ORDER BY commdate ASC");
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
			<span class="date"><?=($mod || (($usr == $lastposter) && ($comr['commID'] == $lastpostid)))?'<a href="news.php?comment='.$comr['commID'].'&amp;edit">Edit</a> | ':''?><?=($mod)?'<a href="news.php?comment='.$comr['commID'].'&amp;delete">Delete</a> | ':''?><?=timezone($comr['commdate'],$_SESSION['tmz'],"jS F Y, H:i A")?></span>
			<div class="text"><?=comment_format($comr['commtext'])?></div>
		</div>
		<? } } else { ?><div class="sorry">There are no comments yet. Be the first!</div><? } ?>
		<div class="comment-box">
		<? if (isset($_SESSION['lvl']) && $comm_closed == 0) { ?>
			<form action="newsaddcomment.php?id=<?=$getnews?>" method="post">
				<fieldset>
					<textarea name="comment" rows="10" cols="76"></textarea>
					<input type="submit" value="Submit" />
				</fieldset>
			</form>
		<? } else if ($comm_closed == 0) { ?><div class="sorry">You must be logged in to comment.</div>
		<? } else { ?><div class="sorry">This news post is closed for comments.</div><? } ?>
		</div>		
	</div>
</div>