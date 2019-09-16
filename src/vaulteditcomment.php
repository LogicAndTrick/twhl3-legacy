<?
	$action = 0;
	if (isset($_GET['editcomment']) && (isset($usr)))
	{
		$commentid = mysql_real_escape_string($_GET['editcomment']);
		$getmapq = mysql_query("SELECT mapcomments.*,uid FROM mapcomments LEFT JOIN users ON userID = poster WHERE commentID = '$commentid'");
		if (mysql_num_rows($getmapq) > 0)
		{
			$getmapr = mysql_fetch_array($getmapq);
			$mapid = $getmapr['map'];
			$posterid = $getmapr['poster'];
			$commusername = $getmapr['uid'];
			$commtext = $getmapr['commtext'];
			$commrating = $getmapr['rating'];
			$getlastq = mysql_query("SELECT * FROM mapcomments WHERE map = '$mapid' ORDER BY commentID DESC LIMIT 1");
			if (mysql_num_rows($getlastq) > 0)
			{
				$getlastr = mysql_fetch_array($getlastq);
				$lastid = $getlastr['commentID'];
				$lastuser = $getlastr['poster'];
				if ((($commentid == $lastid) && ($usr == $lastuser)) || ((isset($lvl)) && $lvl >= 30))
				{
					$action = 1;
				}
			}
		}
	}
	elseif ((isset($_GET['deletecomment'])) && (isset($lvl)) && $lvl >= 30)
	{
		$commentid = mysql_real_escape_string($_GET['deletecomment']);
		$getmapq = mysql_query("SELECT mapcomments.*,uid FROM mapcomments LEFT JOIN users ON userID = poster WHERE commentID = '$commentid'");
		if (mysql_num_rows($getmapq) > 0)
		{
			$getmapr = mysql_fetch_array($getmapq);
			$mapid = $getmapr['map'];
			$posterid = $getmapr['poster'];
			$commusername = $getmapr['uid'];
			$commtext = $getmapr['commtext'];
			$commrating = $getmapr['rating'];
			$action = 2;
		}
	}
	
	$mapq = mysql_query("SELECT * FROM maps WHERE mapID = '$mapid'");
	
	if (mysql_num_rows($mapq) == 0) fail("Map not found.","vault.php");
	if ($action <= 0) fail("No action specified, or you don't have permission.","vault.php");
	
		$mapr = mysql_fetch_array($mapq);
		$mapname = $mapr['name'];
		$allowrating = $mapr['allowrating'];
		
		$rated = false;
		$getrateq = mysql_query("SELECT * FROM  mapcomments WHERE poster = '$posterid' AND map = '$mapid' AND rating > 0");
		if (mysql_num_rows($getrateq) > 0) $rated = true;
?>
<div class="single-center">
	<h1><?=($action==1)?'Edit':'Delete'?> a Comment</h1>
	<p class="single-center-content">
		Here you can <?=($action==1)?'edit':'delete'?> a comment. If the comment has an associated rating, you can modify or remove it if you wish.
	</p>	
</div>	
<div class="single-center" id="gap-fix">
	<h1><?=($action==1)?'Edit':'Delete'?> - Comment <?=$commentid.(($lvl>=30)?' by '.$commusername:'')?></h1>
	<h2><a href="vault.php">Map Vault</a> &gt; <a href="vault.php?map=<?=$mapid?>"><?=$mapname?></a></h2>
<? if ($action==1) { ?>
	<div class="comments">
		<div class="comment-box" style="border-top: 0;">
			<form action="vaultchangecomment.php?id=<?=$commentid?>" method="post">
				<fieldset>
					<textarea name="comment" rows="10" cols="76"><?=$commtext?></textarea>
					<? if ($allowrating && (($rated) && ($commrating > 0)) || ((!$rated) && ($usr == $posterid))) { ?>Rating
					<select name="rating">
						<option value="0"<?=($commrating==0)?' selected="selected"':''?>>Do Not Rate</option>
						<option value="1"<?=($commrating==1)?' selected="selected"':''?>>1 Star</option>
						<option value="2"<?=($commrating==2)?' selected="selected"':''?>>2 Stars</option>
						<option value="3"<?=($commrating==3)?' selected="selected"':''?>>3 Stars</option>
						<option value="4"<?=($commrating==4)?' selected="selected"':''?>>4 Stars</option>
						<option value="5"<?=($commrating==5)?' selected="selected"':''?>>5 Stars</option>
					</select><br /><? } ?>
					<input type="submit" value="Edit" />
				</fieldset>
			</form>
		</div>		
	</div>
<? } else { ?>
	<p class="single-center-content">
		<?=$commtext?>
		<? if ($commrating > 0) { ?><br />
		Rating: <? for ($i = 0; $i < $commrating; $i++) { ?> <img src="images/star_full.png" alt="star" /><? } } ?>
		<form action="vaultdeletecomment.php?id=<?=$commentid?>" method="post"><input id="post" type="submit" value="Delete" size="7" /></form>
	</p>
<? } ?>
</div>