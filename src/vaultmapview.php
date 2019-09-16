<?

$getmap=mysql_real_escape_string($_GET['map']);

$exists = false;
$result = mysql_query("SELECT * FROM maps LEFT JOIN mapcats ON cat = catID LEFT JOIN users ON owner = userID LEFT JOIN mapgames ON game = gameID LEFT JOIN mapengines ON engine = engineID WHERE mapID='$getmap' AND cat != -1");

if (mysql_num_rows($result) > 0)
	$exists = true;

if (($exists) && isset($getmap) && ($getmap!="") && is_numeric($getmap))
{
	$row = mysql_fetch_array($result);
	
	mysql_query("UPDATE maps SET views = views+1 WHERE mapID = '$getmap' LIMIT 1");

	$mod = false;
	if ($_SESSION['lvl'] >= 30) $mod = true;
	
	$lastposter = -1;
	$lastpostid = -1;
	$com_edit = mysql_query("SELECT * FROM mapcomments WHERE map = '$getmap' ORDER BY commtime DESC LIMIT 1");
	if (mysql_num_rows($com_edit) > 0)
	{
		$com_edit_row = mysql_fetch_array($com_edit);
		$lastposter = $com_edit_row['poster'];
		$lastpostid = $com_edit_row['commentID'];
	}
	
	$mapid=$row['mapID'];
	$ownerid=$row['owner'];
	$owner=$row['uid'];
	$avtype=$row['avtype'];
	$name=$row['name'];
	$cat=$row['cat'];
	$catname=$row['catname'];
	$game=$row['game'];
	$gamename=$row['gamename'];
	$engine=$row['engine'];
	$enginename=$row['enginename'];
	$inc=$row['included'];
	$info=$row['info'];
	$date=$row['postdate'];
	$update=$row['editdate'];
	$size=$row['filesize'];
	$file=$row['file'];
	$screen=$row['screen'];
	$pmcomm=$row['pmcomment'];
	$views=$row['views'];
	$downs=$row['downloads'];
	$allowrating=$row['allowrating'];
	$allowupload=$row['allowupload'];
	$numratings=$row['ratings'];
	$totrating=$row['rating'];
	$motmwinner=$row['gotmotm'];
	
	$date=timezone($date,$_SESSION['tmz'],"jS F, Y");
	if ($update && $update > 0 && $update != "") $update=timezone($update,$_SESSION['tmz'],"jS F, Y");
	else $update = 'Never';
	
	$dlsize = 'Hosted Externally';
	if ($size > 1048576)
		$dlsize = (round(($size / 1048576)*100)/100).'MB';
	elseif ($size > 500)
		$dlsize = (round(($size / 1024)*100)/100).'KB';
	elseif ($size > 0)
		$dlsize = $size.' bytes';
		
	$maprating = 0;
	if ($numratings > 0)
		$maprating = ceil(($totrating/$numratings)*2)/2;
	
	$numfullstars = substr($maprating,0,1);
	$halfstar = (strlen($maprating) > 2);
	$numemptystars = 5 - ceil($maprating);
	
	$rated = false;
	$result = mysql_query("SELECT * FROM  mapcomments WHERE poster = '".$_SESSION['usr']."' AND map = '$getmap' AND rating > 0");
	$rater = mysql_num_rows($result);
	if ($rater > 0) $rated = true;
	
	$inclist = ', Nothing included';
	if ($inc > 0) $inclist = '';
	if (($inc-4) >= 0) $inclist = ', BSP'.$inclist;
	$inc %= 4;
	if (($inc-2) >= 0) $inclist = ', MAP'.$inclist;
	$inc %= 2;
	if (($inc-1) >= 0) $inclist = ', RMF'.$inclist;
	$inclist = substr($inclist,2);
	
	$lastmonth = date("n Y",strtotime("last month",gmt("U")));
	$mapmonth = date("n Y",$row['postdate']);
	
	$com_result = mysql_query("SELECT mapcomments.*,uid,avtype FROM mapcomments LEFT JOIN users ON poster = userID WHERE map = '$getmap' ORDER BY commtime ASC");
	$numcoms = mysql_num_rows($com_result);
?>


<div class="single-center">
	<h1><?=$name?></h1>
	<h2><a href="vault.php">Map Vault</a> > <a href="vault.php?id=<?=$cat?>"><?=$catname?></a><? if (($usr == $ownerid) || ($mod)) { ?> > [ <a href="vault.php?edit=<?=$mapid?>">Edit</a> | <a href="vault.php?delete=<?=$mapid?>">Delete</a> ]<? } ?></h2>

	<div class="map-container">
		<div class="map-screenshot">
			<img src="mapvault/<?=($screen > 0)?$mapid.'.'.(($screen == 2)?'png':'jpg'):'noscreen_large.png'?>" alt="map screenshot" /><br />
			<p>
<?
			if ($allowrating==1)
			{ 
				for ($i = 0; $i < $numfullstars; $i++)
					echo '<a href="#"><img src="images/star_full.png" alt="star" /></a>'."\n";
				if ($halfstar)
					echo '<img src="images/star_half.png" alt="star" /></a>'."\n";
				for ($i = 0; $i < $numemptystars; $i++)
					echo '<a href="#"><img src="images/star_empty.png" alt="star" /></a>'."\n";		
				echo "<br />";
				if ($numratings != 0) echo "($numratings votes)";
				else echo "No votes yet";
			}
			else echo "Ratings Disabled";
?>
			</p>
			<p><a href="vault.php?screens=<?=$mapid?>">[More Screenshots]</a></p>
		</div>
		<div class="map-info">
			<span class="map-avatar">
				<img src="<?=getavatar($ownerid,$avtype,true)?>" alt="Avatar" />
			</span>
			<p class="map-author">
				By <a href="user.php?id=<?=$ownerid?>"><?=$owner?></a> (<a href="vault.php?user=<?=$ownerid?>">See more</a>)<br />
				<strong><?=$date?></strong>	<br /><br />
			</p>
			<img src="images/game_<?=$game?>.png" alt="<?=$gamename?>" />
<?
	if (isset($usr) && $usr!="" && ($lastmonth == $mapmonth) && ($cat == 2) && ($usr!=$ownerid))
	{
?>
			<a href="motmaddvote.php?id=<?=$mapid?>" title="Nominate for MOTM"><img src="images/motmvote.png" alt="Nominate this Map for Map of the Month" /></a>
<?
	}
	if ($motmwinner == 1)
	{
?>
			<img src="images/motmwin.png" alt="MOTM Winner!" title="MOTM Winner!" />
<?
	}
?>
			<br />
			<strong><?=$dlsize?></strong> - <?=$inclist?><br />
			<strong><?=$views?></strong> views<br />
			<strong><?=$downs?></strong> downloads<br />
			<strong><?=$numcoms?></strong> comments<br /><br />
			<em>Last edited: <?=$update?></em><br />
			<p class="download-image">
				<a href="vaultdownload.php?download=<?=$mapid?>"><img src="images/download.png" alt="download" /></a>
			</p>	
		</div>
		<div class="map-description">
			<p class="map-description-text">
				<?=comment_format($info)?>
			</p>
		</div>
<?
	$motmuser = 0;
	if (isset($usr) && $usr != "") $motmuser = $usr;
	$motmq = mysql_query("SELECT * FROM motmvotes WHERE voteuser = '$motmuser' AND votemap = $getmap");
	if (mysql_num_rows($motmq) > 0)
	{
?>
		<div style="margin: 12px; color: red; text-align: center;">
			You voted for this map in  <?=date("F",$row['postdate'])?>'s Map of the Month.
		</div>
<?
	}
?>
	</div>
</div>
<div class="single-center" id="gap-fix">
	<?=($numcoms > 0)?'<h1 class="no-bottom-border">Comments</h1>':'<h1>Comments</h1>'?>
	<div class="comments">
		<?
		if ($numcoms > 0) {
			$alt = "-alt";
			while ($row = mysql_fetch_array($com_result)) {
				if ($alt == "") $alt = "-alt";
				else $alt = "";
		?>
		<div class="comment-container<?=$alt?>">
			<span class="avatar"><img src="<?=getavatar($row['poster'],$row['avtype'],true)?>" alt="avatar" /></span>	
			<span class="name"><strong><a href="user.php?id=<?=$row['poster']?>"><?=$row['uid']?></a> says:</strong><? for ($i = 0; $i < $row['rating']; $i++) { ?> <img src="images/star_full.png" alt="star" /><? } ?></span>
			<span class="date">
			<?=($row["attachment"])?'| <a href="mapvault/'.$row["commentID"].'_c.zip">Attachment</A> | ':''?>
			<?=($mod || (($usr == $lastposter) && ($row['commentID'] == $lastpostid)))?(($row["attachment"])?'':'| ').'<a href="vault.php?editcomment='.$row['commentID'].'">Edit</a> | <a href="vault.php?deletecomment='.$row['commentID'].'">Delete</a> | ':''?><?=timezone($row['commtime'],$_SESSION['tmz'],"jS F Y, H:i A")?></span>
			<div class="text"><?=comment_format($row['commtext'])?></div>
		</div>
		<? } } else { ?><div class="sorry">There are no comments yet. Be the first!</div><? } ?>
		<div class="comment-box">
		<? if (isset($_SESSION['lvl'])) { ?>
			<form action="vaultaddcomment.php?id=<?=$getmap?>" method="post" enctype="multipart/form-data">
				<fieldset>
					<textarea name="comment" rows="10" cols="76"></textarea>
					<? if ($allowupload) { ?>Optional File Attachment (for problem maps):<br /><input type="file" size="60" name="attach" /><br /><? } ?>
					<? if (!$rated && ($ownerid!=$_SESSION['usr']) && $allowrating) { ?>Rating
					<select name="rating">
						<option value="0">Do Not Rate</option>
						<option value="1">1 Star</option>
						<option value="2">2 Stars</option>
						<option value="3">3 Stars</option>
						<option value="4">4 Stars</option>
						<option value="5">5 Stars</option>
					</select><br /><? } ?>
					<input type="submit" value="Submit" />
				</fieldset>
			</form>
		<? } else { ?><div class="sorry">You must be logged in to comment.</div><? } ?>
		</div>		
	</div>
</div>
<?
}
else
{
	if (!(isset($getmap) && ($getmap!="") && is_numeric($getmap)))
	{
		$problem = "There is no map specified.";
		$back = "vault.php";	
	}
	elseif (!$exists)
	{
		$problem = "This map does not exist.";
		$back = "vault.php";
	}
	include 'failure.php';
}
?>