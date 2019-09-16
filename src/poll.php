<?

$pollquery = mysql_query("SELECT * FROM polls WHERE isactive = 1 ORDER BY pollID DESC LIMIT 1");
$pollcount = mysql_num_rows($pollquery);

if ($pollcount > 0)  {
	
	$pollrow = mysql_fetch_array($pollquery);
	$pollid = $pollrow['pollID'];
	
	$pollitemq = mysql_query("SELECT * FROM pollitems WHERE itempoll = '$pollid' ORDER BY itemID ASC");
	$numitems = mysql_num_rows($pollitemq);
	
	if ($numitems > 0)  {

		$canvote = false;
		
		if (isset($_SESSION['usr']) && $_SESSION['usr']!="")
		{
			//check the user hasn't voted in this poll yet.
			$polluser=mysql_query("SELECT * FROM users WHERE userID = '$usr'");
			if (mysql_num_rows($polluser) > 0)
			{
				$pur = mysql_fetch_array($polluser);
				$last = $pur['lastvote'];
				if ($last < $pollid) $canvote = true;
			}
		}
	
	$totvoter=mysql_fetch_array(mysql_query("SELECT sum(votes) AS tot, max(votes) AS max FROM pollitems WHERE itempoll = '$pollid'"));
	$totalvotes=$totvoter['tot'];
	$maxvotes=$totvoter['max'];
?>
	<h1>Poll</h1>
	<div class="poll">
		<h2><?=$pollrow['polltitle']?></h2>
		<p><?=$pollrow['pollsubtitle']?></p>
<?
		if ($canvote)
		{
?>
		<form action="/pollvote.php" method="post">
		<fieldset>
<?
		}
		while ($itemrow = mysql_fetch_array($pollitemq)) {
			if ($canvote)
			{
?>
			<input type="radio" name="pollvote" value="<?=$itemrow['itemID']?>" /> <?=$itemrow['item']?><br />
<?
			}
			else
			{
?>
			<p class="result-item"><?=$itemrow['item']?></p>
			<div style="width: <?=ceil(($itemrow['votes']/$maxvotes)*117)+13?>px;" class="result-graph"><?=$itemrow['votes']?></div>
<?
			}
		}
		if ($canvote)
		{
?>
				<input type="hidden" name="return" value="<?=$_SERVER['PHP_SELF']."?".str_replace("&","&amp;",$_SERVER['QUERY_STRING'])?>" />
				<input class="vote" type="submit" value="Vote" />
			</fieldset>
		</form>
<?
		}
?>
	</div>
<?
	}
}
?>