<?
	include 'top.php';
	if (isset($_GET['help']))
		include 'awardhelp.php';
	else
	{
?>
<div class="single-center">
	<h1>Awards Showroom</h1>
	<p class="single-center-content">
		Welcome to the TWHL Awards page! Here, we congratulate our users for various things, including Forum, Tutorial and Map Vault contributions, as well as Competition trophies and some other special awards. Congratulations to everyone here!
	</p>
	<p class="single-center-content">
		Want to get your hands on one of these excellent acclamations? Check out the criteria <a href="awards.php?help">here</a>.
	</p>	
</div>	
<div class="single-center" id="gap-fix">
	<h1>The Awards</h1>				
	<table class="awards-page">
<?
		$awardq = mysql_query("SELECT awards.*,awardtypes.*,userID,uid FROM awards LEFT JOIN awardtypes ON awardtype = awardtypeID LEFT JOIN users ON awarduser = userID WHERE awarduser > 0 ORDER BY awardID DESC");
		if (mysql_num_rows($awardq) > 0)
		{
			$counter = 0;
			while ($awrow = mysql_fetch_array($awardq))
			{
				if ($counter == 0) {
					echo '<tr>';
				}
?>
			<td>
				<p class="awards-name">
					<a href="user.php?id=<?=$awrow['awarduser']?>"><?=$awrow['uid']?></a><? if (isset($lvl) && $lvl >= 40) { ?> <a href="#">[M]</a><? } ?><br />
				</p>
				<p class="awards-image">
					<img src="http://twhl.info/gfx/award_<?=$awrow['awardshortname']?>.gif" alt="<?=$awrow['awardtypename']?> Award" />
				</p>
				<p class="awards-info">
					<? if ($awrow['awardreason'] != "") { ?><em><?=$awrow['awardreason']?></em><br /><? } ?>
					<?=$awrow['awardwhen']?>
				</p>	
			</td>
<?
				$counter++;
				if ($counter >= 4) {
					$counter = 0;
					echo '</tr>';
				}
			}
			if ($counter > 0) {
				echo '</tr>';
			}
?>
	</table>		
</div>
<?
		}
	}
	include 'bottom.php';
?>