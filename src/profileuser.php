<div class="single-center" style="display: <?=($currenttab=='user')?'inline':'none'?>" id="user-tab">
	<h1>Members | <?=$user?></h1>
	<span class="page-index">
		<span style="padding: 3px 0 3px 6px; color: black;">User</span> |
		<a href="javascript:tabswitcher(new Array('journal-tab','map-tab','user-tab'))">Journals</a> |
		<a href="javascript:tabswitcher(new Array('map-tab','journal-tab','user-tab'))">Maps</a>
	</span>	
	<h2>General Info<? if (isset($usr) && $usrid == $usr) { ?> | <a href="user.php?control&edit">Edit</a><? } ?></h2>
	<span class="left-avatar">
		<img src="<?=$avatar?>" alt="avatar" /> <br />
		<?=$access?><br />
		<a href="privmsg.php?send=<?=$usrid?>">Send PM</a> <br />
		<table class="profile-stats">
			<tr>
				<td>Logins (per day) </td>
				<td><?=$logins.' ('.round($logins/$days,1).')'?></td>	
			</tr>	
			<tr>
				<td>Forum posts</td>
				<td><?=$posts.' (' . round($posts/$days,1).')'?></td>	
			</tr>	
			<tr>	
				<td>Shouts</td>
				<td><?=$shouts.' (' . round($shouts/$days,1).')'?></td>
			</tr>	
			<tr>
				<td>Maps</td>
				<td><?=$maps.' (' . round($maps/$days,1).')'?></td>
			</tr>
			<tr>	
				<td>MV comments</td> 
				<td><?=$mvcoms.' (' . round($mvcoms/$days,1).')'?></td>
			</tr>
			<tr>		
				<td>Journal entries</td>
				<td><?=$journs.' (' . round($journs/$days,1).')'?></td>
			</tr>	
		</table>
	</span>	
		
	<p class="left-info">
		<? if ($rname != "") { ?><strong>Name</strong>: <?=$rname?><br /><? } ?>
		<strong>Joined</strong>: <?=$dated?> (<?=$days?> days ago) <br />
		<? if ($website != "") { ?><strong>Website</strong>: <?=$website?><br /><? } ?>
		<? if ($location != "") { ?><strong>Location</strong>: <?=$location?><br /><? } ?>
		<? if ($job != "") { ?><strong>Occupation</strong>: <?=$job?><br /><? } ?>
		<? if ($interests != "") { ?><strong>Interests</strong>: <?=$interests?><br /><? } ?>
		<br />
		
		<? if ($email != "") { ?><strong>E-mail</strong>: <a href="#"><?=$email?></a> <br /><? } ?>
		<? if ($msn != "") { ?><strong>MSN</strong>: <?=$msn?><br /><? } ?>
		<br />
		
		<em><?=$skillhl?></em><br />
		<em><?=$skillsrc?></em><br />
	</p>
<?
	$trophyq = mysql_query("SELECT * FROM compwins LEFT JOIN compos ON wincomp = compID WHERE winuser = '$usrid'");
	$awardq = mysql_query("SELECT * FROM awards LEFT JOIN awardtypes ON awardtype = awardtypeID WHERE awarduser = '$usrid'");
	if ((mysql_num_rows($trophyq) > 0) || (mysql_num_rows($awardq) > 0))
	{
		$counter = 0;
?>
	<h2 style="border-top: 1px solid #daa134">Trophies</h2>
	<table class="map-page">
<?
		while($trrow = mysql_fetch_array($trophyq)) {
			if ($counter == 0) {
				echo '<tr>';
			}
?>
			<td>
				<p class="map-name">
					<a href="#"><?=$trrow['compname']?></a><br />
				</p>
				<p class="map-image">
					<a href="#"><img src="gfx/cupbig_<?=$trrow['winplace']?>.jpg" alt="trophy" /></a>
				</p>
				<p class="map-info">
					<?=timezone($trrow['compclose'],$_SESSION['tmz'],"m Y")?>
				</p>	
			</td>
<?
			$counter++;
			if ($counter >= 3) {
				$counter = 0;
				echo '</tr>';
			}
		}
		while($awrow = mysql_fetch_array($awardq)) {
			if ($counter == 0) {
				echo '<tr>';
			}
?>
			<td>
				<p class="map-name">
					<a href="#"><?=$awrow['awardtypename']?> Award</a><br />
				</p>
				<p class="map-image">
					<a href="#"><img src="gfx/award_<?=$awrow['awardshortname']?>.gif" alt="<?=$awrow['awardshortname']?> award" /></a>
				</p>
				<p class="map-info">
					<?=$awrow['awardwhen']?>
				</p>
			</td>
<?
			$counter++;
			if ($counter >= 3) {
				$counter = 0;
				echo '</tr>';
			}
		}
		if ($counter > 0) {
			echo '</tr>';
		}
?>
	</table>
<?
	}
	if ($bio != "")
	{
?>
	<h2 style="border-top: 1px solid #daa134">Biography</h2>
	<?=bio_format($bio)?>
<?
	}
?>
</div>