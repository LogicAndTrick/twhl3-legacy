<div class="single-center">
	<h1>Competitions</h1>
	<p class="single-center-content">
		Welcome to TWHL Competitions! Here's where we post new challenges for you to try. Once you've completed your entry, all you need to do is send it in. There are great prizes to be won (icons on your profile page)! There's nothing to lose - you can only gain experience from trying, and if you win... well... just enter!
	</p>

	<p class="single-center-content">
		Remember not to leave submitting an entry to the last second, because contests close at around 2400 GMT-8:00.
	</p>	
	<h2 style="border-top: 1px solid #daa134">Current Competitions</h2>

<?
	$thenow = gmt("U");
	$activeq = mysql_query("SELECT * FROM compos LEFT JOIN comptypes ON comptype = comptypeID WHERE compclose > '$thenow' AND compopen > 0 ORDER BY compclose DESC");
	if (mysql_num_rows($activeq) > 0)
	{
?>
	<table class="compo-index">
		<tr>
			<th>Name</th>
			<th>Type</th>
			<th>Opened On</th>
		</tr>
<?
		while ($actr = mysql_fetch_array($activeq))
		{
?>
		<tr>
			<td><a href="competitions.php?comp=<?=$actr['compID']?>"><?=$actr['compname']?></a></td>
			<td align="left"><?=$actr['comptypename']?></td>
			<td align="left"><?=timezone($actr['compopen'],$_SESSION['tmz'],"jS F, Y")?></td>
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
	<p class="single-center-content">
		There are no competitions running at the moment. <?=(isset($lvl) && ($lvl >= 40))?'<a href="competitions.php?new">Create a new Competition</a>.':'Keep an eye out for one in the future!'?>
	</p>
<?
	}
?>
<h2 style="border-top: 1px solid #daa134">Past Competitions</h2>
	<p class="single-center-content">
		Take a look at the past briefs for our competitions, as well as the winners.
	</p>
<?
	$pastq = mysql_query("SELECT * FROM compos LEFT JOIN comptypes ON comptype = comptypeID WHERE compclose < '$thenow' AND compopen > 0 ORDER BY compclose DESC");
	if (mysql_num_rows($pastq) > 0)
	{
?>
	<table class="compo-index">
		<tr>
			<th>Name</th>
			<th>Type</th>
			<th>Closed On</th>
		</tr>
<?
		while ($par = mysql_fetch_array($pastq))
		{
?>
		<tr>
			<td><a href="competitions.php?results=<?=$par['compID']?>"><?=$par['compname']?></a></td>
			<td align="left"><?=$par['comptypename']?></td>
			<td align="left"><?=timezone($par['compclose'],$_SESSION['tmz'],"jS F, Y")?></td>
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
	<p class="single-center-content">
		There are no past competitions.
	</p>
<?
	}
?>
</div>


	
	
	
