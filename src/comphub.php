<?php
	$getcomp = mysql_real_escape_string($_GET['hub']);
	
	$compq = mysql_query("SELECT * FROM compos WHERE compID = '$getcomp' AND comptype > 0");
	
	$mod = false;
	if (isset($lvl) && $lvl >= 35) $mod = true;
	
	$allowhub = false;
	$is_judge = false;
	$judgeq = mysql_query("SELECT * FROM compjudges WHERE judgecomp = '$getcomp' AND judgeuser = '$usr'");
	if ((mysql_num_rows($judgeq) > 0) || (isset($lvl) && $lvl >= 35)) $allowhub = true;
	if (mysql_num_rows($judgeq) > 0) $is_judge = true;
	
	$judged = false;
	$winq = mysql_query("SELECT * FROM compwins WHERE wincomp = '$getcomp'");
	if ((mysql_num_rows($winq) > 0) && !$stillopen) $judged = true;
	
	if (mysql_num_rows($compq) == 0) fail("Competition not found.","competitions.php");
	if (!$allowhub) fail("You don't have permission to view this page.","competitions.php");
	
	$comprow = mysql_fetch_array($compq);
	
	$timeleft = $comprow['compclose'] - gmt("U");
	$stillopen = false;
	if ($timeleft > 0) $stillopen = true;
	
	$currenttab = 'brief';
	if (isset($_GET['judges'])) $currenttab = 'judges';
?>
<div class="single-center" style="margin-bottom: 0;">
	<h1>Competition <?=$getcomp?> - <?=$comprow['compname']?></h1>
	<h2><a href="competitions.php?comp=<?=$getcomp?>">Competition Brief</a></h2>
	<p class="single-center-content"> 
		Welcome to the TWHL Compo Entry Hub. From here, you'll be able to see all the entries to the current competition. Admins are able to edit all aspects of the competition, including start date, end date and other details.
	</p>
</div>	
<div class="single-center" style="display: <?=($currenttab=='brief')?'block':'none'?>" id="brief-tab">
	<h1>Entries and Details</h1>
	<h2>The Brief | <a href="javascript:tabswitcher(new Array('entries-tab','brief-tab','tools-tab','vote-tab','judge-tab'))">Entries</a> | <a href="javascript:tabswitcher(new Array('tools-tab','entries-tab','brief-tab','vote-tab','judge-tab'))">Edit</a> | <a href="javascript:tabswitcher(new Array('judge-tab','tools-tab','entries-tab','brief-tab','vote-tab'))">Judges</a> | <a href="javascript:tabswitcher(new Array('vote-tab','tools-tab','entries-tab','brief-tab','judge-tab'))">Vote</a></h2>
	<h3>Info</h3>
<?
	if (trim($comprow['comppic']) != "")
	{
?>
	<div class="notes">
		<p class="single-center-content" id="center">	
			<img src="compopics/compo_<?=$comprow['comppic']?>" />
		</p>
	</div>
<?
	}
?>
	<p class="single-center-content">
		<?=comment_format($comprow['compdesc'])?>
	</p>	
</div>
<div class="single-center" id="entries-tab" style="display: none;">
	<h1>The Entries</h1>
	<h2><a href="javascript:tabswitcher(new Array('brief-tab','vote-tab','tools-tab','entries-tab','judge-tab'))">The Brief</a> | Entries | <a href="javascript:tabswitcher(new Array('tools-tab','entries-tab','brief-tab','vote-tab','judge-tab'))">Edit</a> | <a href="javascript:tabswitcher(new Array('judge-tab','tools-tab','entries-tab','brief-tab','vote-tab'))">Judges</a> | <a href="javascript:tabswitcher(new Array('vote-tab','tools-tab','entries-tab','brief-tab','judge-tab'))">Vote</a></h2>
<?
	$entq = mysql_query("SELECT * FROM compentries LEFT JOIN users ON entryuser = userID WHERE entrycomp = '$getcomp' ORDER BY entrydate ASC");
	$numents = mysql_num_rows($entq);
	if ($numents > 0)
	{
?>
	<table class="compo-entries">
		<tr>
			<th>Author</th>
			<th>Map</th>
			<th>Comments</th>
			<th>Date</th>
			<th>Size</th>
			<?=($mod)?'<th>Del</th>':''?>
			<?=($is_judge)?'<th>Val</th>':''?>
		</tr>
<?
		while ($enr = mysql_fetch_array($entq))
		{
			if (file_exists("uploads/".$enr['entryfile']))
			{
				$size = filesize("uploads/".$enr['entryfile']);
				if ($size > 1048576)
					$dlsize = (round(($size / 1048576)*100)/100).' MB';
				elseif ($size > 500)
					$dlsize = (round(($size / 1024)*100)/100).' KB';
				elseif ($size > 0)
					$dlsize = $size.' B';
			}
			else $dlsize = "N/A";
?>
		<tr>
			<td><a href="user.php?id=<?=$enr['entryuser']?>"><?=$enr['uid']?></a></td>
			<td><a href="<?=($enr['entryfile'] != '')?'uploads/'.$enr['entryfile']:$enr['entrylink']?>"><?=$enr['entryname']?></a></td>
			<td><?=linesplitter($enr['entrytext'],25)?></td>
			<td><?=timezone($enr['entrydate'],$_SESSION['tmz'],"jS F Y")?></td>
			<td class="center"><?=$dlsize?></td>
			<?=($mod)?'<td class="center"><a href="competitions.php?deleteentry='.$enr['entryID'].'">[D]</a></td>':''?>
			<?=($is_judge)?'<td class="center" style="background-color: '.(($enr['entryvalid']==1)?'green':'red').'"><a href="compentryvalid.php?id='.$enr['entryID'].'">[T]</a></td>':''?>
		</tr>	
<?
		}
?>
	</table>
<?
	}
?>
	<p class="single-center-content" id="center"> 	
		Total Entries: <?=$numents?>
	</p>
</div>

<div class="single-center" id="tools-tab" style="display: none;">
	<h1>Edit a Competition</h1>
	<h2><a href="javascript:tabswitcher(new Array('brief-tab','vote-tab','tools-tab','entries-tab','judge-tab'))">The Brief</a> | <a href="javascript:tabswitcher(new Array('entries-tab','brief-tab','tools-tab','vote-tab','judge-tab'))">Entries</a> | Edit | <a href="javascript:tabswitcher(new Array('judge-tab','tools-tab','entries-tab','brief-tab','vote-tab'))">Judges</a> | <a href="javascript:tabswitcher(new Array('vote-tab','tools-tab','entries-tab','brief-tab','judge-tab'))">Vote</a></h2>
<?
	if ($mod)
	{
?>
	<form action="compedit.php?id=<?=$getcomp?>" method="post" enctype="multipart/form-data">
		<fieldset class="new-thread">
			<p class="single-center-content">
				<input class="right" size="30" type="text" name="compname" value="<?=$comprow['compname']?>" />Name of Compo:<br />
				<select class="right" name="comptype">
					<option value="1"<?=($comprow['comptype']==1)?' selected="selected"':''?>>Full Map</option>
					<option value="2"<?=($comprow['comptype']==2)?' selected="selected"':''?>>Map From Base</option>
				</select>Type:<br />
				<select class="right" name="compgame">
					<option value="1"<?=($comprow['compgame']==1)?' selected="selected"':''?>>Goldsource</option>
					<option value="2"<?=($comprow['compgame']==2)?' selected="selected"':''?>>Source</option>
					<option value="3"<?=($comprow['compgame']==3 && $comprow['compjudgetype']==3)?' selected="selected"':''?>>Both (judged together)</option>
					<option value="4"<?=($comprow['compgame']==3 && $comprow['compjudgetype']==2)?' selected="selected"':''?>>Both (judged seperately)</option>
				</select>Engine:<br />
				<input class="right" size="30" type="text" name="compstart" value="<?=date("d m Y",$comprow['compopen'])?>" />Start Date (DD MM YYYY):<br />
				<input class="right" size="30" type="text" name="compend" value="<?=date("d m Y",$comprow['compclose'])?>" />End Date (DD MM YYYY):<br />
			</p>
			<p class="single-center-content">
				Outline:<br />
				<textarea rows="10" cols="76" name="compdesc"><?=$comprow['compdesc']?></textarea>
			</p>
			<p class="single-center-content">
				Additional Restrictions:<br />
				<textarea rows="5" cols="76" name="comprest"><?=$comprow['comprestrictions']?></textarea>
			</p>
			<p class="single-center-content">
				<input class="right" size="30" type="file" name="comppic" />Image (Currently, <?=($comprow['comppic']!="")?'<a href="compopics/compo_'.$comprow['comppic'].'">'.$comprow['comppic'].'</a>.':'no image uploaded.'?>):<br />
				<input class="right" size="30" type="file" name="compfile" />Download (Currently, <?=($comprow['compexample']!="")?'<a href="compodl/'.$comprow['compexample'].'">'.$comprow['compexample'].'</a>.':'no file uploaded.'?>):<br />
			</p>
			<p class="single-center-content-center">
				<input type="submit" value="Edit" />
			</p>
		</fieldset>
	</form>
<?
		}
		else
		{
?>
	<p class="single-center-content-center"> 	
		You are not allowed to edit this competition.
	</p>
<?
	}
?>
</div>
<div class="single-center" id="judge-tab" style="display: <?=($currenttab=='judges')?'block':'none'?>;">
	<h1>Vote for winners</h1>
	<h2><a href="javascript:tabswitcher(new Array('brief-tab','vote-tab','tools-tab','entries-tab','judge-tab'))">The Brief</a> | <a href="javascript:tabswitcher(new Array('entries-tab','brief-tab','tools-tab','vote-tab','judge-tab'))">Entries</a> | <a href="javascript:tabswitcher(new Array('tools-tab','entries-tab','brief-tab','vote-tab','judge-tab'))">Edit</a> | Judges | <a href="javascript:tabswitcher(new Array('vote-tab','tools-tab','entries-tab','brief-tab','judge-tab'))">Vote</a></h2>
	
<?
	$judq = mysql_query("SELECT * FROM compjudges LEFT JOIN users ON judgeuser = userID WHERE judgecomp = '$getcomp' ORDER BY judgeID ASC");
	if (mysql_num_rows($judq) > 0)
	{
?>
	<table class="compo-entries">
		<tr>
			<th>Judge</th>
			<th>User Level</th>
			<?=($mod)?'<th>Remove</th>':''?>
		</tr>
<?
		while ($jur = mysql_fetch_array($judq))
		{
?>
		<tr>
			<td><a href="user.php?id=<?=$jur['judgeuser']?>"><?=$jur['uid']?></a></td>
			<td><?=axslvl($jur['lvl'])?></a></td>
			<?=($mod)?'<td class="center"><a href="compremovejudge.php?id='.$jur['judgeID'].'">[D]</a></td>':''?>
		</tr>	
<?
		}
?>
	</table>
<?
	}
	else
	{
?>
	<p class="single-center-content-center"> 	
		There are no judges for this competition yet.
	</p>
<?
	}
	
	if ($mod)
	{
		$jueq = mysql_query("SELECT * FROM compjudges LEFT JOIN users ON judgeuser = userID WHERE judgecomp != '$getcomp' ORDER BY judgeID LIMIT 15");
		$adeq = mysql_query("SELECT * FROM users WHERE lvl >= 20 ORDER BY lvl DESC, userID DESC LIMIT 15");
?>
	<form action="compaddjudge.php?id=<?=$getcomp?>" method="post">
		<fieldset class="new-thread">
			<p class="single-center-content">
				Add a new judge to this competition:<br />
				<input type="radio" name="choose" value="recent" checked="checked" onclick="javascript:tabswitcher(new Array('recent-radio','admins-radio','userid-radio'))" /> From recent judges of competitions<br />
				<input type="radio" name="choose" value="admins" onclick="javascript:tabswitcher(new Array('admins-radio','recent-radio','userid-radio'))" /> From a list of Admins and Moderators<br />
				<input type="radio" name="choose" value="userid" onclick="javascript:tabswitcher(new Array('userid-radio','recent-radio','admins-radio'))" /> By entering a User ID<br />
				<div style="text-align: center;" id="recent-radio">
					<select name="rec">
<?
		$counter = 0;
		while ($juer = mysql_fetch_array($jueq))
		{
			$counter++;
?>
						<option value="<?=$juer['userID']?>"><?=$juer['uid']?></option>
<?
		}
		if ($counter == 0)
		{
?>
						<option value="0">No Previous Judges Found</option>
<?
			}
?>
					</select>
				</div>
				<div style="text-align: center; display: none;" id="admins-radio">
					<select name="adm">
<?
		$counter = 0;
		while ($ader = mysql_fetch_array($adeq))
		{
			$counter++;
?>
						<option value="<?=$ader['userID']?>"><?=$ader['uid']?></option>
<?
		}
		if ($counter == 0)
		{
?>
						<option value="0">No Admins or Moderators Found</option>
<?
		}
?>
					</select>
				</div>
				<div style="text-align: center; display: none;" id="userid-radio">
					<input type="text" name="use" />
				</div>
			<p class="single-center-content-center">
				<input type="submit" value="Add" />
			</p>
		</fieldset>
	</form>
<?
	}
	else
	{
?>
	<p class="single-center-content" id="center"> 	
		You are not allowed to manage the judges of this competition.
	</p>
<?
	}
?>
</div>
<div class="single-center" id="vote-tab" style="display: none;">
	<h1>Vote for winners</h1>
	<h2><a href="javascript:tabswitcher(new Array('brief-tab','vote-tab','tools-tab','entries-tab','judge-tab'))">The Brief</a> | <a href="javascript:tabswitcher(new Array('entries-tab','brief-tab','tools-tab','vote-tab','judge-tab'))">Entries</a> | <a href="javascript:tabswitcher(new Array('tools-tab','entries-tab','brief-tab','vote-tab','judge-tab'))">Edit</a> | <a href="javascript:tabswitcher(new Array('judge-tab','tools-tab','entries-tab','brief-tab','vote-tab'))">Judges</a> | Vote</h2>
<?
	if ($stillopen)
	{
?>
	<p class="single-center-content" id="center"> 	
		This Competition is still open, and votes cannot be made until it is closed.
	</p>
<?
	}
	elseif ($judged)
	{
?>
	<p class="single-center-content" id="center"> 	
		This Competition has already been judged, so voting is closed. See the results <a href="competitions.php?results=<?=$getcomp?>">here</a>.
	</p>
<?
	}
	elseif (!$is_judge)
	{
?>
	<p class="single-center-content" id="center"> 	
		You are not a judge of this competition, so you cannot vote for this competition. If you want, you can add yourself as a judge, but this means that you must judge ALL the maps, not just some.
	</p>
<?
	}
	else
	{
?>
	<p class="single-center-content" id="center"> 	
		Here is where you judge all the maps in the competition. If you see nothing here, you must go into the "entries" tab and select the eligable entries (this cannot be done automatically because of disqualifications, repeat entries, etc.)
	</p>
<?
	}
?>
</div>