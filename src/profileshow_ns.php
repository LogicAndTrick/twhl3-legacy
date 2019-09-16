<?php
		
	if (isset($_GET['name']))
	{
		$usrnm=mysql_real_escape_string($_GET['name']);
		$result = mysql_query("SELECT * FROM users WHERE uid='$usrnm'");
	}
	elseif (isset($_GET['id']))
	{
		$usrid=mysql_real_escape_string($_GET['id']);
		$result = mysql_query("SELECT * FROM users WHERE userID='$usrid'");
	}
	
	$row = mysql_fetch_array($result);
	$user=$row['uid'];
	$access=axslvl($row['lvl']);
	$logins=$row['log'];
	$usrid=mysql_real_escape_string($row['userID']);
	$datej=$row['joined'];
	$dated=date("jS F Y",$datej);
	$yes=1;		
	
	$avtype=$row['avtype'] ;
	$avatar=getresizedavatar($usrid,$avtype,100);
		
	$email=$row['email'];
	$allow=$row['allowemail'];
	$lastlog=$row['lastlogin'];
	$bio=$row['bio'];
	$posts=$row['stat_posts'];
	$shouts=$row['stat_shouts'];
	$maps=$row['stat_maps'];
	$mvcoms=$row['stat_mvcoms'];
	$journs=$row['stat_journals'];
	$jcoms=$row['stat_jcoms'];
	$tuts=$row['stat_tuts'];
	$tcoms=$row['stat_tutcoms'];
	$projs=$row['stat_projects'];
	$pcoms=$row['stat_pcoms'];
	$ptracks=$row['stat_ptracking'];
	$rname=$row['info_realname'];
	$website=$row['info_website'];
	$job=$row['info_job'];
	$interests=$row['info_interests'];
	$location=$row['info_location'];
	$langs=$row['info_lang'];
	$aim=$row['info_aim'];
	$msn=$row['info_msn'];
	$xfire=$row['info_xfire'];
	
	$lastl=gmt(U)-$lastlog;
	$lastd=$lastl/86400;
	
	if ($lastd <= 1)
		$lastlogday="Today";
	else if ($lastd < 2)
		$lastlogday="1 Day Ago";
	else
		$lastlogday=ceil($lastd) . " Days Ago";
		
	if ($website=="")
		$website = "";
	elseif (substr($website,0,7)=="http://")
		$website = '<a href="' . $website . '">' . $website . '</a>';
	else
		$website = '<a href="http://' . $website . '">http://' . $website . '</a>';
		
		
	if ($allow!=1)
		$email = "";
	
	$dayz=gmt(U)-$datej;
	$days=ceil($dayz/86400);
	
?>
<div class="single-center">
	<h1>Members | Ant</h1>
	<h2><a href="#">Edit this Profile</a></h2>
	<span class="left-avatar">
		<img src="<?=$avatar?>" alt="avatar" /> <br />
		Adminstrator <br />
		<a href="#">Send PM</a> <br />
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
		
		<em>Half-Life mapper, coder and modeller</em> <br />
		<em>Source mapper, coder and modeller</em> <br />
	</p>
<?
	$usermapq = mysql_query("SELECT * FROM (SELECT * FROM maps WHERE owner = '$usrid' ORDER BY RAND()) as mqr LIMIT 3");
	if (mysql_num_rows($usermapq) > 0)
	{
?>
	<h2 style="border-top: 1px solid #daa134">Maps</h2>
	<table class="map-page">
		<tr>
<?
		while ($mapr = mysql_fetch_array($usermapq)) 
		{
			$pdate=timezone($mapr['postdate'],$_SESSION['tmz'],"H:i, d M y");
			$rating = 0;
			if ($mapr['ratings'] > 0)
				$rating = ceil((($mapr['rating']/$mapr['ratings'])*2))/2;
			
			$numfullstars = substr($rating,0,1);
			$halfstar = (strlen($rating) > 2);
?>
			<td>
				<p class="map-name">
					<a href="vault.php?map=<?=$mapr['mapID']?>"><?=$mapr['name']?></a><br />
					<? 
					if ($mapr['allowrating']==1) { 
					for ($i = 0; $i < $numfullstars; $i++) { ?>
					<img src="images/star_full.png" alt="star" />
					<? } if ($halfstar) { ?><img src="images/star_half.png" alt="star" /><? } ?>
					<?=($mapr['ratings']==0)?"No Votes Yet":"(".$mapr['ratings'].")"?>
					<? } else { ?>Ratings Disabled<? } ?>
				</p>
				<p class="map-image">
					<a href="vault.php?map=<?=$mapr['mapID']?>"><img src="mapvault/<?=($mapr['screen'] > 0)?$mapr['mapID'].'_small.'.(($mapr['screen'] == 2)?'png':'jpg'):'none_small.gif'?>" alt="map image" /></a>
				</p>
				<p class="map-info">
					<?=$pdate?>
				</p>	
			</td>
<?
		}
?>
		</tr>
	</table>	
	<p class="single-center-content" id="center">
		<a href="vault.php?user=<?=$usrid?>">See all</a>
	</p>
<?
	}
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
	$journalq = mysql_query("SELECT * FROM journals WHERE ownerID = '$usrid' ORDER BY journaldate DESC LIMIT 3");
	if (mysql_num_rows($journalq) > 0)
	{
		$alt = "-alt";
?>
	<h2 style="border-top: 1px solid #daa134">Recent Journals</h2>
	<div class="journals">
<?
		while ($jrow = mysql_fetch_array($journalq))
		{
			if ($alt == "") $alt = "-alt";
			else $alt = "";
?>
		<div class="journal-container<?=$alt?>">
			<span class="date"><?=timezone($jrow['journaldate'],$_SESSION['tmz'],"jS F Y, H:i A")?></span>
			<div class="profile-text">
				<p>
					<?=comment_format($jrow['journaltext'])?>
				</p>
			</div>
		</div>
<?
		}
?>
	</div>
<?
	}
?>
</div>