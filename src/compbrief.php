<?php
	$getcomp = mysql_real_escape_string($_GET['comp']);
	
	$compq = mysql_query("SELECT * FROM compos LEFT JOIN comptypes ON comptype = comptypeID LEFT JOIN compgames ON compgame = compgameID LEFT JOIN compjudgetypes ON compjudgetype = judgetypeID WHERE compID = '$getcomp' AND comptype > 0");
	
	if (mysql_num_rows($compq) == 0) fail("Competition not found.","competitions.php");

	$comprow = mysql_fetch_array($compq);
	$days = 0;
	$hours = 0;
	$minutes = 0;
	$seconds = 0;
	$timeleft = $comprow['compclose'] - gmt("U");
	$countdownseed = $timeleft;
	$stillopen = false;
	if ($timeleft > 0)
	{
		$stillopen = true;
		$days = floor($timeleft/86400);
		$timeleft -= ($days * 86400);
		$hours = floor($timeleft/3600);
		$timeleft -= ($hours * 3600);
		$minutes = floor($timeleft/60);
		$timeleft -= ($minutes * 60);
		$seconds = floor($timeleft);
	}
	if ($comprow['compopen'] > gmt("U")) $stillopen = false;
		
	$days = str_pad($days,2,"0",STR_PAD_LEFT);
	$hours = str_pad($hours,2,"0",STR_PAD_LEFT);
	$minutes = str_pad($minutes,2,"0",STR_PAD_LEFT);
	$seconds = str_pad($seconds,2,"0",STR_PAD_LEFT);
	
	$judged = false;
	$winq = mysql_query("SELECT * FROM compwins WHERE wincomp = '$getcomp'");
	if ((mysql_num_rows($winq) > 0) && !$stillopen) $judged = true;
	
	$allowhub = false;
	$judgeq = mysql_query("SELECT * FROM compjudges WHERE judgecomp = '$getcomp' AND judgeuser = '$usr'");
	if ((mysql_fetch_array($judgeq) > 0) || (isset($lvl) && $lvl >= 40)) $allowhub = true;
		
?>
<div class="single-center">
	<h1>Competition <?=$getcomp?> - <?=$comprow['compname']?></h1>
	<h2><a href="competitions.php">Competition Index</a> &gt; <?=$comprow['compname']?><?=($allowhub)?' &gt; <a href="competitions.php?hub='.$getcomp.'">Competition Hub</a>':''?><?=($judged)?' &gt; <a href="competitions.php?results='.$getcomp.'">View Results</a>':''?></h2>
<?
		if ($stillopen)
		{
?>
	<div class="timer-container">
		<p class="timer" id="countdown-timer">
			<script language="javascript">countdown(<?=$countdownseed?>,"countdown-timer")</script><?//="$days:$hours:$minutes:$seconds"?>
		</p>	
		<p class="timer-info">
			Days:Hours:Minutes:Seconds<br />
			Time Remaining
		</p>
	</div>
<?
		}
?>
	<p class="right-info"> 
		Name: <?=$comprow['compname']?><br />
<?
		if ($comprow['compopen'] > gmt("U"))
		{
?>
		Opens: <?=timezone($comprow['compopen'],$_SESSION['tmz'],"jS F Y")?><br />
<?
		}
		else
		{
?>
		Opened: <?=timezone($comprow['compopen'],$_SESSION['tmz'],"jS F Y")?><br />
<?
		}
		if ($comprow['compclose'] > gmt("U"))
		{
?>
		Closing: <strong><?=timezone($comprow['compclose'],$_SESSION['tmz'],"jS F Y")?></strong><br />
<?
		}
		else
		{
?>
		Closed: <?=timezone($comprow['compclose'],$_SESSION['tmz'],"jS F Y")?><br />
<?
		}
?>
		Type: <?=$comprow['comptypename']?><br />
<?
		if ($comprow['compgamename'])
		{
?>
		Game: <?=$comprow['compgamename']?><br />
<?
		}
		if ($comprow['judgetype'])
		{
?>
		Judged: <?=$comprow['judgetype']?><br />
<?
		}
		if (trim($comprow['compexample']) != "")
		{
?>
		Competition Download: <a href="compodl/<?=$comprow['compexample']?>">Download Here</a><br />
<?
		}
?>
		<br />
<?
		$judgeq = mysql_query("SELECT * FROM compjudges LEFT JOIN users ON judgeuser = userID WHERE judgecomp = '$getcomp'");
		if (mysql_num_rows($judgeq) > 0)
		{
?>
		<strong>Judges</strong><br />
<?
			while ($jur = mysql_fetch_array($judgeq))
			{
				$avatar = getavatar($jur['judgeuser'],$jur['avtype'],true);
?>
		<img src="<?=$avatar?>" alt="<?=$jur['uid']?>" title="<?=$jur['uid']?>" />
<?
			}
		}
?>
	</p>
</div>	
<div class="single-center" id="gap-fix">
	<h1>Competition <?=$getcomp?> - <?=$comprow['compname']?> Brief</h1>
	<h3>Info</h3>
<?
		if (trim($comprow['comppic']) != "")
		{
?>
	<div class="notes">
		<p class="single-center-content-center">	
			<img src="compopics/compo_<?=$comprow['comppic']?>" />
		</p>
	</div>
<?
		}
?>
	<p class="single-center-content">
		<?=comment_format($comprow['compdesc'])?>
	</p>
<?
		if ($stillopen)
		{
?>
	<p class="single-center-content-center">
		<a href="competitions.php?submit=<?=$getcomp?>">Submit Entry</a>
	</p>
<?
		}
?>
</div>
<div class="single-center">
	<h1>TWHL Competition Rules and Regulations</h1>
	<?=$comprow['comprestrictions']?>
</div>