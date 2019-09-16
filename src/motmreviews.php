<?
	function reviewarraymaker($numlinks,$front)
	{
		$arr = "";
		if ($front == "main") $arr .= "'info-tab'";
		else $arr .= "'review-$front-tab','info-tab'";
		
		for ($i=1;$i<=$numlinks;$i++)
		{
			if ($front != $i) $arr .= ",'review-$i-tab'";
		}
		
		return $arr;
	}
	
	function reviewlinkmaker($numlinks,$current)
	{
		$links = "";
		if ($current == "main") $links .= "Winner Info";
		else $links .= '<a href="javascript:tabswitcher(new Array('.reviewarraymaker($numlinks,"main").'))">Winner Info</a>';
		
		for ($i=1;$i<=$numlinks;$i++)
		{
			if ($current == $i) $links .= " | Review $i";
			else $links .= ' | <a href="javascript:tabswitcher(new Array('.reviewarraymaker($numlinks,$i).'))">Review '.$i.'</a>';
		}
		
		return $links;
	}

	$getmotm = mysql_real_escape_string($_GET['id']);
	
	$motmq = mysql_query("SELECT motm.*,userID,avtype,name,uid,userID,mapID,gamename,postdate FROM motm LEFT JOIN maps ON map = mapID LEFT JOIN mapgames ON maps.game = gameID LEFT JOIN users ON owner = userID WHERE motmID = '$getmotm'");
	if (mysql_num_rows($motmq) <= 0) fail("This MOTM entry doesn't exist","motm.php");
	
	$revq = mysql_query("SELECT * FROM motmreviews LEFT JOIN users ON reviewer = userID WHERE motm = '$getmotm'");
	$numrevs = mysql_num_rows($revq);
	
	$focus = "main";
	if (isset($_GET['focus']) && is_numeric($_GET['focus']) && $_GET['focus'] <= $numrevs && $_GET['focus'] > 0) $focus = mysql_real_escape_string($_GET['focus']);
	
	$motmr = mysql_fetch_array($motmq);
	
	$monthyear = date("F Y",$motmr['postdate']);
	$avatar = getresizedavatar($motmr['userID'],$motmr['avtype'],100);
	$mapname = $motmr['name'];
	$author = $motmr['uid'];
	$authorid = $motmr['userID'];
	$gamename = $motmr['gamename'];
	$mapid = $motmr['mapID'];
	$image = $motmr['thumb'];
	
	$arch = $motmr['arch']*10;
	$tex = $motmr['tex']*10;
	$amb = $motmr['amb']*10;
	$light = $motmr['light']*10;
	$game = $motmr['game']*10;
	$total = $motmr['total'];
?>
<div class="single-center" id="info-tab" style="display: <?=($focus=="main")?'block':'none'?>;">
	<h1>Map of the Month - <?=$monthyear?></h1>
	<h2><?=reviewlinkmaker($numrevs,"main")?></h2>
	<div class="motm-container">
		<div class="motm-info">
			<span class="right-avatar">
				<img alt="avatar" src="<?=$avatar?>"/>
			</span>	
			<p class="right-info">
				<br/>
				<?=$mapname?><br/>
				By <a href="user.php?id=<?=$authorid?>"><?=$author?></a><br/>
				<strong><?=$monthyear?></strong><br/>
				For <strong><?=$gamename?></strong><br/>
			</p>
			<p class="download-image">
				<a href="vaultdownload.php?download=<?=$mapid?>"><img alt="download" src="images/download.png"/></a>
			</p>
		</div>
		<div class="motm-screenshot">
			<img alt="Screenshot" src="<?=$image?>"/>
			<p>
				<a href="vault.php?map=<?=$mapid?>">[Map Vault Page]</a>
			</p>
		</div>	
	</div>
	<h3>Overall Score</h3>
	<div class="motm-score">
		<table class="motm-score-table">
			<tr>
				<td>
					<span style="font-size: 22px; color: <?=tri_colour($arch)?>"><?=$arch?>%</span><br/>
					<span style="font-size: 10px;">Architecture</span>
				</td>
				<td>
					<span style="font-size: 22px; color: <?=tri_colour($tex)?>"><?=$tex?>%</span><br/>
					<span style="font-size: 10px;">Texturing</span>
				</td>
				<td>
					<span style="font-size: 22px; color: <?=tri_colour($amb)?>"><?=$amb?>%</span><br/>
					<span style="font-size: 10px;">Ambience</span>
				</td>
				<td>
					<span style="font-size: 22px; color: <?=tri_colour($light)?>"><?=$light?>%</span><br/>
					<span style="font-size: 10px;">Lighting</span>
				</td>
				<td>
					<span style="font-size: 22px; color: <?=tri_colour($game)?>"><?=$game?>%</span><br/>
					<span style="font-size: 10px;">Gameplay</span>
				</td>
				<td>
					<span style="font-size: 22px; text-align: right;">Total - <span style="color: <?=tri_colour($total)?>"><?=$total?>%</span></span>
				</td>
			</tr>
		</table>	
	</div>
</div>
<?
	$counter = 1;
	while ($revr = mysql_fetch_array($revq))
	{
		$rarch = $revr['arch']*10;
		$rtex = $revr['tex']*10;
		$ramb = $revr['amb']*10;
		$rlight = $revr['light']*10;
		$rgame = $revr['game']*10;
		$rtotal = $revr['total'];
		
		$ruser = $revr['uid'];
		$ruserid = $revr['userID'];
		$ravatar = getresizedavatar($revr['userID'],$revr['avtype'],100);
		
		$content = $revr['content'];
?>
<div class="single-center" style="display: <?=($focus==$counter)?'block':'none'?>;" id="review-<?=$counter?>-tab">
	<h1>Map of the Month - <?=$monthyear?></h1>
	<h2><?=reviewlinkmaker($numrevs,$counter)?></h2>
	<span class="right-avatar">
		<img alt="avatar" src="<?=$ravatar?>"/>
	</span>	
	<p class="right-info">
		<br/>
		Reviewed by <a href="user.php?id=<?=$ruserid?>"><?=$ruser?></a>
		&nbsp;<br/>
	</p>
	<h3><?=$ruser?>'s Score</h3>
	<div class="motm-score">
		<table class="motm-score-table">
			<tr>
				<td>
					<span style="font-size: 22px; color: <?=tri_colour($rarch)?>"><?=$rarch?>%</span><br/>
					<span style="font-size: 10px;">Architecture</span>
				</td>
				<td>
					<span style="font-size: 22px; color: <?=tri_colour($rtex)?>"><?=$rtex?>%</span><br/>
					<span style="font-size: 10px;">Texturing</span>
				</td>
				<td>
					<span style="font-size: 22px; color: <?=tri_colour($ramb)?>"><?=$ramb?>%</span><br/>
					<span style="font-size: 10px;">Ambience</span>
				</td>
				<td>
					<span style="font-size: 22px; color: <?=tri_colour($rlight)?>"><?=$rlight?>%</span><br/>
					<span style="font-size: 10px;">Lighting</span>
				</td>
				<td>
					<span style="font-size: 22px; color: <?=tri_colour($rgame)?>"><?=$rgame?>%</span><br/>
					<span style="font-size: 10px;">Gameplay</span>
				</td>
				<td>
					<span style="font-size: 22px; text-align: right;">Total - <span style="color: <?=tri_colour($rtotal)?>"><?=$rtotal?>%</span></span>
				</td>
			</tr>
		</table>	
	</div>
	<p class="single-center-content">
		<?=motm_format($content)?>
	</p>
<?
	$mod = false;
	if ($_SESSION['lvl'] >= 35) $mod = true;
	
	$revid = $revr['reviewID'];
	
	$lastposter = -1;
	$lastpostid = -1;
	$comeditq = mysql_query("SELECT * FROM motmcomments WHERE commmotm = '$revid' ORDER BY commdate DESC LIMIT 1");
	if (mysql_num_rows($comeditq) > 0)
	{
		$comeditr = mysql_fetch_array($comeditq);
		$lastposter = $comeditr['commuser'];
		$lastpostid = $comeditr['commID'];
	}
	$comq = mysql_query("SELECT * FROM motmcomments LEFT JOIN users ON commuser = userID WHERE commmotm = '$revid' ORDER BY commdate ASC");
	$numcoms = mysql_num_rows($comq);
?>
	<?=($numcoms > 0)?'<h2 style="border-top: 1px solid #daa134; border-bottom: 0;">Comments</h2>':'<h2 style="border-top: 1px solid #daa134;">Comments</h2>'?>
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
			<span class="date"><?=($mod || (($usr == $lastposter) && ($comr['commID'] == $lastpostid)))?'<a href="motm.php?comment='.$comr['commID'].'&amp;focus='.$counter.'&amp;edit">Edit</a> | ':''?><?=($mod)?'<a href="motm.php?comment='.$comr['commID'].'&amp;focus='.$counter.'&amp;delete">Delete</a> | ':''?><?=timezone($comr['commdate'],$_SESSION['tmz'],"jS F Y, H:i A")?></span>
			<div class="text"><?=comment_format($comr['commtext'])?></div>
		</div>
		<? } } else { ?><div class="sorry">There are no comments yet. Be the first!</div><? } ?>
		<div class="comment-box">
		<? if (isset($_SESSION['lvl'])) { ?>
			<form action="motmaddcomment.php?id=<?=$revid?>&amp;focus=<?=$counter?>" method="post">
				<fieldset>
					<textarea name="comment" rows="10" cols="76"></textarea>
					<input type="submit" value="Submit" />
				</fieldset>
			</form>
		<? } else { ?><div class="sorry">You must be logged in to comment.</div><? } ?>
		</div>		
	</div>
</div>
<?
		$counter++;
	}
?>