<?
	$action = 0;
	if (isset($_GET['edit']) && (isset($usr)))
	{
		$mapid = mysql_real_escape_string($_GET['edit']);
		$action = 1;

	}
	elseif ((isset($_GET['delete'])) && (isset($usr)))
	{
		$mapid = mysql_real_escape_string($_GET['delete']);
		$action = 2;
	}
	
	$mapq = mysql_query("SELECT * FROM maps WHERE mapID = '$mapid'");
	if (mysql_num_rows($mapq) > 0)
	{
		$mapr = mysql_fetch_array($mapq);
		$mapowner = $mapr['owner'];
		if ($mapowner != $usr && $lvl < 30) $action = 0;
	}
	else $action = 0;
	
	if ($action > 0)
	{
		$mapname = $mapr['name'];
		$mapcat = $mapr['cat'];
		$mapgame = $mapr['game'];
		$mapinc = $mapr['included'];
		$mapinfo = $mapr['info'];
		$mapfile = $mapr['file'];
		$mappmcomm = $mapr['pmcomment'];
		$mapallowrating = $mapr['allowrating'];
		$mapallowupload = $mapr['allowupload'];
		
		$maprmf = false;
		$mapmap = false;
		$mapbsp = false;
		if (($mapinc-4) >= 0) $mapbsp = true;
		$mapinc %= 4;
		if (($mapinc-2) >= 0) $mapmap = true;
		$mapinc %= 2;
		if (($mapinc-1) >= 0) $maprmf = true;
?>
<? if ($action==1) { ?>
<div class="single-center">
	<h1>Edit a Map</h1>
	<p class="single-center-content">
		Here you can edit your map. You can change the name, file, category, and even the game. Use the form below to make your changes.
	</p>	
</div>
<div class="single-center" id="gap-fix">
	<h1>Edit Map <?=$mapid?> - <?=$mapname?></h1>
	<div class="filter">
		<form action="vaultchangemap.php?id=<?=$mapid?>" method="post" enctype="multipart/form-data">
			<div class="filter-left">
				What game is your map for?<br />
				<select name="game">
<?
						$res2 = mysql_query("SELECT * FROM mapgames ORDER BY gameorder ASC");
						while($rowe = mysql_fetch_array($res2)) {
?>
						<option value="<?=$rowe['gameID']?>"<?=($mapgame == $rowe['gameID'])?' selected="selected"':''?>><?=$rowe['gamename']?></option>
<?
						}
?>
				</select>
				<br />
				<br />
				What category is it?<br />
				<select name="category">
<?
						$res = mysql_query("SELECT * FROM mapcats ORDER BY catorder ASC");
						while($rowg = mysql_fetch_array($res)) {
?>
						<option value="<?=$rowg['catID']?>"<?=($mapcat == $rowg['catID'])?' selected="selected"':''?>><?=$rowg['catname']?></option>
<?
						}
?>
				</select>
			</div>
			<div class="filter-right">
				Choose your options:<br />
				<input type="checkbox" name="ratings" style="margin-left: 5px;" <?=($mapallowrating > 0)?' checked="checked"':''?>/> Allow ratings<br />
				<input type="checkbox" name="uploads" style="margin-left: 5px;" <?=($mapallowupload > 0)?' checked="checked"':''?>/> Allow uploads<br />
				<input type="checkbox" name="pmcomment" style="margin-left: 5px;" <?=($mappmcomm > 0)?' checked="checked"':''?>/> PM on comment<br />
			</div>
			<div class="filter-center">
				Map name:<br />
				<input name="name" type="text" size="20" value="<?=$mapname?>"/>
				<div class="filter-include">
					Includes:<br />
					<input type="checkbox" name="RMF" <?=($maprmf)?' checked="checked"':''?>/> RMF<br />
					<input type="checkbox" name="BSP" <?=($mapbsp)?' checked="checked"':''?>/> BSP<br />
					<input type="checkbox" name="MAP" <?=($mapmap)?' checked="checked"':''?>/> MAP<br />
				</div>
			</div>
			<div class="filter-bottom" id="mapsubmit-bottom">
				<input type="radio" name="link" value="file" onclick="javascript:mapsubtoggle(1)" <?=($mapfile == "")?'checked="checked" ':''?>/> File Upload (maximum 2MB) (Leave blank to stay the same)
				<input type="radio" name="link" value="link" onclick="javascript:mapsubtoggle(0)" <?=($mapfile != "")?'checked="checked" ':''?>/> Link to file
				<div id="upload-div" style="display: <?=($mapfile == "")?'block':'none'?>">
					<input type="file" size="80" name="upload"/>
				</div>
				<div id="link-div" style="display: <?=($mapfile != "")?'block':'none'?>">
					<input type="text" size="80" name="uplink" value="<?=$mapfile?>"/>
				</div><br />
				Upload your screenshot. You can add extra screenshots later. (Leave blank to stay the same)<br />
				<input type="file" size="80" name="image" /><br /><br />
				Give a description of your map.<br />
				<textarea cols="76" rows="10" name="details"><?=$mapinfo?></textarea><br />
				<input type="submit" value="Submit"/>
			</div>
		</form>
	</div>
</div>
<? } else { ?>
<div class="single-center">
	<h1>Delete a Map</h1>
	<p class="single-center-content">
		Here you can delete your map. Be careful, when you delete your map, all files are deleted too.
	</p>	
</div>
<div class="single-center" id="gap-fix">
	<h1>Delete Map <?=$mapid?> - <?=$mapname?></h1>
	<p class="single-center-content">
		<?=comment_format($mapinfo)?>
		<form action="vaultdeletemap.php?id=<?=$mapid?>" method="post"><input id="post" type="submit" value="Delete" size="7" /></form>
	</p>
</div>
<?
		}
	}
	else fail("No action specified, or you don't have permission to do this.","vault.php");
?>