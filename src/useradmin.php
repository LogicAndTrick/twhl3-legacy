<?
	if (!(isset($lvl) && ($usr >= 40))) fail("You are not allowed view this page.","index.php");
	$getuser = mysql_real_escape_string($_GET['manage']);
	if (!(isset($getuser) && ($getuser != ""))) fail("No user specified!.","admin.php&user");
	$peditq = mysql_query("SELECT * FROM users LEFT JOIN userlevels ON levelnum = lvl WHERE userID = '$getuser'");
	if (mysql_num_rows($peditq) == 0) fail("User doesn't exist.","admin.php&user");
	
	$pdr = mysql_fetch_array($peditq);
	
	//----------------------------------------------------------------------//
	
	$user=$pdr['uid'];
	$userlvl=$pdr['lvl'];
	
	if ($lvl < $userlvl) fail("You can't manage users of higher level than you.","index.php");
	
	$access=$pdr['levelname'];	//axslvl($pdr['lvl']);
	$logins=$pdr['log'];
	$usrid=$pdr['userID'];
	$datej=$pdr['joined'];
	$dated=date("jS F Y",$datej);
	$yes=1;
	
	if (($pdr['allowtitle'] > 0 || $pdr['lvl'] >= 20) && $pdr['usetitle'] > 0 && $pdr['title'] != "") $access = $pdr['title'];
	
	$allowtitle = $pdr['allowtitle'];
	$usetitle = $pdr['usetitle'];
	$title = $pdr['title'];
	
	$avtype=$pdr['avtype'] ;
	$avatar=getresizedavatar($usrid,$avtype,100);
		
	$email=$pdr['email'];
	$allow=$pdr['allowemail'];
	$lastlog=$pdr['lastlogin'];
	$bio=$pdr['bio'];
	$lastbrow=$pdr['lastbrowser'];
	$lastplace=$pdr['lastplace'];
	$prohits=$pdr['stat_profilehits'];
	$posts=$pdr['stat_posts'];
	$shouts=$pdr['stat_shouts'];
	$maps=$pdr['stat_maps'];
	$mvcoms=$pdr['stat_mvcoms'];
	$journs=$pdr['stat_journals'];
	$jcoms=$pdr['stat_jcoms'];
	$tuts=$pdr['stat_tuts'];
	$tcoms=$pdr['stat_tutcoms'];
	$projs=$pdr['stat_projects'];
	$pcoms=$pdr['stat_pcoms'];
	$ptracks=$pdr['stat_ptracking'];
	$ttracks=$pdr['stat_ttracking'];
	$rname=$pdr['info_realname'];
	$website=$pdr['info_website'];
	$job=$pdr['info_job'];
	$interests=$pdr['info_interests'];
	$location=$pdr['info_location'];
	$langs=$pdr['info_lang'];
	$aim=$pdr['info_aim'];
	$msn=$pdr['info_msn'];
	$xfire=$pdr['info_xfire'];
	
	$hlmap=$pdr['skill_hl_map'];
	$hlmodel=$pdr['skill_hl_model'];
	$hlcode=$pdr['skill_hl_code'];
	$srcmap=$pdr['skill_src_map'];
	$srcmodel=$pdr['skill_src_model'];
	$srccode=$pdr['skill_src_code'];
	
	$skillhl = "";
	$skillsrc = "";
	
	$numhls = $hlmap + $hlmodel + $hlcode;
	$numsrc = $srcmap + $srcmodel + $srccode;
	
	if ($numhls > 0) $skillhl = "Half-Life ".(($hlmap)?("mapper".(($numhls > 1)?(($numhls > 2)?", ":" and "):"")):"").(($hlmodel)?"modeller".(($hlcode)?" and coder":""):(($hlcode)?"coder":""));
	
	if ($numsrc > 0) $skillsrc = "Source ".(($srcmap)?("mapper".(($numsrc > 1)?(($numsrc > 2)?", ":" and "):"")):"").(($srcmodel)?"modeller".(($srccode)?" and coder":""):(($srccode)?"coder":""));
	
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
	
	//----------------------------------------------------------------------//
	
	
	$trackq = mysql_query("SELECT * FROM threadtracking LEFT JOIN threads ON trackthread = threadID WHERE trackuser = '$getuser' ORDER BY trackID DESC");
	$alertq = mysql_query("SELECT * FROM alertuser LEFT JOIN alerttypes ON alerttype = alerttypeID LEFT JOIN users ON alerter = userID WHERE alertuser = '$getuser' ORDER BY alertdate DESC");
	$inq = mysql_query("SELECT * FROM pminbox LEFT JOIN users ON pmfrom = userID WHERE pmto = '$getuser' AND isnew >= 0 ORDER BY pmtime DESC");
	$arq = mysql_query("SELECT * FROM pminbox LEFT JOIN users ON pmfrom = userID WHERE pmto = '$getuser' AND isnew = '-1' ORDER BY pmtime DESC");
	
	//----------------------------------------------------------------------//
	
	function statline($name,$num,$div)
	{
		return '<tr><td>'.$name.'</td><td style="text-align: center;">'.$num.'</td><td style="text-align: center;">'.round($num/$div,1).'</td></tr>';
	}
	function statsforall($stats,$div)
	{
		$allstats = "";
		foreach ($stats as $key => $value)
		{
			$allstats .= statline($key,$value,$div)."\n";
		}
		return $allstats;
	}
	
	$allstats = array('Logins' => $logins,'Profile Hits' => $prohits,'Shouts' => $shouts,'Forum Posts' => $posts,'Threads Tracking' => $ttracks,'Maps' => $maps,'Vault Comments' => $mvcoms,'Journals' => $journs,'Journal Comments' => $jcoms,'Tutorials' => $tuts,'Tutorial Comments' => $tcoms,'Projects' => $projs,'Project Comments' => $pcoms,'Projects Tracking' => $ptracks);
	
	// current = current tab
	// tabs = all tabs including current, in an array:
	// tab id => tab name
	function tabswitchlinkmaker($current,$alltabs)
	{
		$str = '';
		foreach($alltabs as $key => $value)
		{
			$allkeys = "'$key'";
			foreach($alltabs as $key2 => $value2) if ($key2 != $key) $allkeys .= ",'$key2'";
			if ($key != $current) $str .= ' | <a href="javascript:tabswitcher(new Array('.$allkeys.'))">'.$value.'</a>';
			else $str .= ' | '.$value;
		}
		$str=substr($str,3);
		return $str;
	}
	
	$tabs = array('profile-tab' => 'View Profile','alerts-tab' => 'Alerts','track-tab' => 'Tracking','pm-tab' => 'PMs','edit-tab' => 'Edit Profile','avatar-tab' => 'Avatar','stats-tab' => 'Stats','pass-tab' => 'Password','awards-tab' => 'Awards','ban-tab' => 'Ban','oblit-tab' => 'Obliterate');
	
	$tabcont = 'profile';
	if (isset($_GET['alerts'])) $tabcont = 'alerts';
	elseif (isset($_GET['track'])) $tabcont = 'track';
	elseif (isset($_GET['pm'])) $tabcont = 'pm';
	elseif (isset($_GET['edit'])) $tabcont = 'edit';
	elseif (isset($_GET['avatar'])) $tabcont = 'avatar';
	elseif (isset($_GET['stats'])) $tabcont = 'stats';
	elseif (isset($_GET['pass'])) $tabcont = 'pass';
	elseif (isset($_GET['awards'])) $tabcont = 'awards';
	elseif (isset($_GET['ban'])) $tabcont = 'ban';
	elseif (isset($_GET['oblit'])) $tabcont = 'oblit';
?>

<div class="single-center" style="margin-bottom: 0;">
	<h1>User Management</h1>
	<h2><a href="forums.php">Back to Admin Panel</a> | <a href="user.php?id=<?=$usr?>">View User's Profile</a></h2> 	
	<span class="left-control-image">
		<img src="images/useradmin_large.png" alt="large spanner" />
	</span>
	<p class="single-center-content">
		Welcome to the User Management Panel. This is a very powerful tool for controlling all aspects of a user, from editing their profile - including access levels, username, custom titles, profile details, email - to changing their avatar, modifying their stats and resetting their password. You can also give the user awards, view their tracked topics, user alerts, and private messages. You can also ban the user, temporarily or permanently (can be removed). You can also Obliterate the user, which bans the user, and deletes from your selection of content (forum content, maps, tutorials, etc).
	</p>	
</div>
<div class="single-center" style="display: <?=($tabcont=='profile')?'block':'none'?>;" id="profile-tab">
	<h1>View Profile</h1>
	<h2><?=tabswitchlinkmaker('profile-tab',$tabs)?></h2>
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
<div class="single-center" style="display: <?=($tabcont=='alerts')?'block':'none'?>;" id="alerts-tab">
	<h1>TWHL User Alerts</h1>
	<h2><?=tabswitchlinkmaker('alerts-tab',$tabs)?></h2>
<?
	if (mysql_num_rows($alertq) > 0)
	{
?>
	<table class="user-alerts">
		<tr>
			<th>From</th>
			<th>Date</th>
			<th>Subject</th>
			<th>Message</th>
			<th>Delete</th>
		</tr>
<?
		while ($alertr = mysql_fetch_array($alertq))
		{
?>
		<tr>
			<td><a href="user.php?id=<?=$alertr['alerter']?>"><?=$alertr['uid']?></a></td>
			<td><?=timezone($alertr['alertdate'],$_SESSION['tmz'],"jS F Y")?></td>
			<td><a href="<?=$alertr['typelink']?><?=$alertr['alertlink']?>"><?=$alertr['typetext']?></a></td>
			<td><?=$alertr['alertcontent']?></td>
			<td><a href="useralertdelete.php?id=<?=$alertr['alertID']?>">[D]</a></td>
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
		<?=$user?> doesn't have any alerts.
	</p>
<?
	}
?>
</div>
<div class="single-center" style="display: <?=($tabcont=='track')?'block':'none'?>;" id="track-tab">
	<h1>Thread Tracking</h1>
	<h2><?=tabswitchlinkmaker('track-tab',$tabs)?></h2>
<?
	if (mysql_num_rows($trackq) > 0)
	{
?>
	<table class="thread-watch">
		<tr>
			<th>Thread</th>
			<th>Started Tracking</th>
			<th>Replies</th>
			<th>Delete</th>
		</tr>
<?
		while ($trackr = mysql_fetch_array($trackq))
		{
?>
		<tr>
			<td><a href="forums.php?thread=<?=$trackr['trackthread']?>&amp;page=last"><?=$trackr['name']?></a></td>
			<td><?=timezone($trackr['trackdate'],$_SESSION['tmz'],"jS F Y")?></td>
			<td><strong><?=$trackr['isnew']?></strong> <em>since last view</em></td>
			<td><a href="usertrack.php?id=<?=$trackr['trackID']?>">[D]</a></td>
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
		<?=$user?> isn't tracking any threads.
	</p>
<?
		}
?>
</div>
<div class="single-center" style="display: <?=($tabcont=='pm')?'block':'none'?>;" id="pm-tab">
	<h1>Private Messages</h1>
	<h2><?=tabswitchlinkmaker('pm-tab',$tabs)?></h2>
	<h3>Inbox</h3>
<?
	if (mysql_num_rows($inq) > 0)
	{
?>
		<table class="private-messages">
			<tr>

				<th>From</th>
				<th>Subject</th>
				<th>Received</th>
				<th>Manage</th>
			</tr>
<?
		while ($inr = mysql_fetch_array($inq))
		{
?>
			<tr<?=($inr['isnew']==1)?' class="recent-message':''?>">
				<td><? if ($inr['pmfrom']!=0) { ?><a href="user.php?id=<?=$inr['pmfrom']?>"><?=$inr['uid']?></a><? } else { ?>Anonymous<? } ?></td>
				<td align="left"><a href="privmsg.php?view=<?=$inr['pmID']?>"><?=$inr['pmsubject']?></a></td>
				<td align="left"><?=timezone($inr['pmtime'],$_SESSION['tmz'],"jS F Y")?></a></td>
				<td><a href="privdelete.php?id=<?=$inr['pmID']?>">[D]</a> <a href="privarchive.php?id=<?=$inr['pmID']?>">[A]</a></td>
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
			<?=$user?> has no Private Messages in their inbox.
		</p>
<?
	}
?>
	<h3>Archive</h3>
<?
	if (mysql_num_rows($arq) > 0)
	{
?>
	<table class="private-messages">
		<tr>

			<th>From</th>
			<th>Subject</th>
			<th>Received</th>
			<th>Delete</th>
		</tr>
<?
		while ($arr = mysql_fetch_array($arq))
		{
?>
		<tr>
			<td><? if ($arr['pmfrom']!=0) { ?><a href="user.php?id=<?=$arr['pmfrom']?>"><?=$arr['uid']?></a><? } else { ?>Anonymous<? } ?></td>
			<td align="left"><a href="privmsg.php?view=<?=$arr['pmID']?>"><?=$arr['pmsubject']?></a></td>
			<td align="left"><?=timezone($arr['pmtime'],$_SESSION['tmz'],"jS F Y")?></a></td>
			<td><a href="privdelete.php?id=<?=$arr['pmID']?>">[D]</a></td>
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
		<?=$user?> has no archived Private Messages.
	</p>
<?
	}
?>
</div>
<div class="single-center" style="display: <?=($tabcont=='edit')?'block':'none'?>;" id="edit-tab">
	<h1>Edit profile</h1>
	<h2><?=tabswitchlinkmaker('edit-tab',$tabs)?></h2>
<?
		$avat = $pdr['avtype'];
?>
	<form action="useradminprofileedit.php" method="post" enctype="multipart/form-data">
		<fieldset class="new-thread">
			<p class="single-center-content">
				<input type="hidden" name="user_id" value="<?=$usrid?>" />
				<input class="right" size="30" type="text" name="username" value="<?=$user?>" />Username:<br />
				<select class="right" name="level">
<?
		$userlevelq = mysql_query("SELECT * FROM userlevels WHERE levelnum <= $lvl ORDER BY levelnum ASC");
		while ($userlevelr = mysql_fetch_array($userlevelq))
		{
?>
					<option value="<?=$userlevelr['levelnum']?>"<?=($userlvl==$userlevelr['levelnum'])?' selected="selected"':''?>><?=$userlevelr['levelname']?></option>
<?
		}
?>
				</select>User Level:<br />
				<input class="right" style="margin: 5px" type="checkbox" name="allowtitle"<?=($pdr['allowtitle']==1)?' checked="checked"':''?> /><span style="float:right;">Allow custom title</span><br /><br />
				<input class="right" style="margin: 5px" type="checkbox" name="usetitle"<?=($pdr['usetitle']==1)?' checked="checked"':''?> /><span style="float:right;">Use custom title</span><br /><br />
				<input class="right" size="30" type="text" name="title" value="<?=$pdr['title']?>" />Custom Title:<br />
				<select class="right" name="timezone">
<?
		for ($i=-12;$i<13;$i++)
		{
?>
					<option value="<?=$i?>"<?=($pdr['timezone']==$i)?' selected="selected"':''?>>GMT<?=(($i>0)?"+$i":(($i==0)?"":$i))." - ".timezone(gmt("U"),$i,"H:i")?></option>
<?
		}
?>
				</select>Timezone:<br />
				<input class="right" size="30" type="text" name="email" value="<?=$pdr['email']?>" />Email:<br />
				<input class="right" style="margin: 5px" type="checkbox" name="showemail"<?=($pdr['allowemail']==1)?' checked="checked"':''?> /><span style="float:right;">Show Email</span><br />
			</p>
			<p class="single-center-content">
				<input class="right" size="30" type="text" name="realname" value="<?=$pdr['info_realname']?>" />Real Name:<br />
				<input class="right" size="30" type="text" name="location" value="<?=$pdr['info_location']?>" />Location:<br />
				<input class="right" size="30" type="text" name="language" value="<?=$pdr['info_lang']?>" />Languages Spoken:<br />
				<input class="right" size="30" type="text" name="occupation" value="<?=$pdr['info_job']?>" />Occupation:<br />
				<input class="right" size="30" type="text" name="website" value="<?=$pdr['info_website']?>" /><span style="float:right;">http://</span>Website:<br />
				<input class="right" size="30" type="text" name="interests" value="<?=$pdr['info_interests']?>" />Interests:<br />
				<select class="right" name="gend">
					<option value="M"<?=($pdr['gender']=="M")?' selected="selected"':''?>>M</option>
					<option value="F"<?=($pdr['gender']=="F")?' selected="selected"':''?>>F</option>
					<option value="?"<?=($pdr['gender']=="?")?' selected="selected"':''?>>?</option>
				</select>Gender:
			</p>
			<p class="single-center-content">
				<select class="right" name="forumposts">
					<option value="10"<?=($pdr['opt_forum_posts']==10)?' selected="selected"':''?>>10</option>
					<option value="20"<?=($pdr['opt_forum_posts']==20)?' selected="selected"':''?>>20</option>
					<option value="30"<?=($pdr['opt_forum_posts']==30)?' selected="selected"':''?>>30</option>
					<option value="40"<?=($pdr['opt_forum_posts']==40)?' selected="selected"':''?>>40</option>
					<option value="50"<?=($pdr['opt_forum_posts']==50 || $pdr['opt_forum_posts']==0)?' selected="selected"':''?>>50</option>
				</select>Number of Posts to show in forums:<br />
				<input class="right" style="margin: 5px" type="checkbox" name="forumavatars"<?=($pdr['opt_forum_avatar']==1)?' checked="checked"':''?> /><span style="float:right;">Use Small Avatars in Forums</span><br />
			</p>
			<p class="single-center-content">
				<input class="right" size="30" type="text" name="msn" value="<?=$pdr['info_msn']?>" />MSN:<br />
				<input class="right" size="30" type="text" name="aim" value="<?=$pdr['info_aim']?>" />AIM:<br />
				<input class="right" size="30" type="text" name="xfire" value="<?=$pdr['info_xfire']?>" />Xfire Username:<br />
				<input class="right" size="30" type="text" name="steam" value="<?=$pdr['info_steam']?>" />Steam Username:<br />
			</p>
			<p class="single-center-content">
				<input class="right" style="margin: 5px" type="checkbox" name="hl1code"<?=($pdr['skill_hl_code']==1)?' checked="checked"':''?> /><span style="float:right;">Code</span>
				<input class="right" style="margin: 5px" type="checkbox" name="hl1model"<?=($pdr['skill_hl_model']==1)?' checked="checked"':''?> /><span style="float:right;">Model</span>
				<input class="right" style="margin: 5px" type="checkbox" name="hl1map"<?=($pdr['skill_hl_map']==1)?' checked="checked"':''?> /><span style="float:right;">Map</span>
				Half-Life 1 Engine Skills:
			</p>
			<p class="single-center-content">
				<input class="right" style="margin: 5px" type="checkbox" name="srccode"<?=($pdr['skill_src_code']==1)?' checked="checked"':''?> /><span style="float:right;">Code</span>
				<input class="right" style="margin: 5px" type="checkbox" name="srcmodel"<?=($pdr['skill_src_model']==1)?' checked="checked"':''?> /><span style="float:right;">Model</span>
				<input class="right" style="margin: 5px" type="checkbox" name="srcmap"<?=($pdr['skill_src_map']==1)?' checked="checked"':''?> /><span style="float:right;">Map</span>
				Source Skills:
			</p>
			<p class="single-center-content">
				Bio:<br />
				<textarea rows="10" cols="76" name="bio"><?=$pdr['bio']?></textarea>
			</p>
			<p class="single-center-content-center">
				<input type="submit" value="Modify Profile" />
			</p>
		</fieldset>
	</form>
</div>
<div class="single-center" style="display: <?=($tabcont=='avatar')?'block':'none'?>;" id="avatar-tab">
	<h1>Change your Avatar</h1>
	<h2><?=tabswitchlinkmaker('avatar-tab',$tabs)?></h2>
	<p class="single-center-content">
		Pick an avatar from the list below, or upload an image to be your avatar. Your image will be automatically resized. Images which are inappropriate will be deleted and may result in a ban from the site.
	</p>
	<form action="useradminchangeavatar.php" method="post" enctype="multipart/form-data">
		<fieldset class="new-thread">
			<input type="hidden" name="user_id" value="<?=$usrid?>" />
			<table style="border: 0; width: 657px;">
<?
	if ($avat == 0) $avat == 1;
	for ($i=1;$i<31;$i++)
	{
		if ($i%5==1) echo '<tr>';
?>
				<td style="text-align: center;"><img src="gfx/avatars/<?=str_pad($i, 3, "0", STR_PAD_LEFT)?>.gif" alt="Avatar" /><br /><input type="radio" name="avselect" value="<?=$i?>" <?=($avat==$i)?'checked="checked" ':''?>/></td>
<?
		if ($i%5==0) echo '</tr>';
	}
?>
			</table>
			<p class="single-center-content-center">
				<input type="radio" name="avselect" value="-1" <?=($avat<0)?'checked="checked" ':''?>/> Image Upload<br />
				<input type="file" name="avup" size="40" />
			</p>
			<p class="single-center-content-center">
				<input type="submit" value="Change Avatar" />
			</p>
		</fieldset>
	</form>
</div>
<div class="single-center" style="display: <?=($tabcont=='stats')?'block':'none'?>;" id="stats-tab">
	<h1>Statistics</h1>
	<h2><?=tabswitchlinkmaker('stats-tab',$tabs)?></h2>
	<table class="single-row">
		<tr><th>Statistic</th><th>Number</th><th>Per day (Average)</th></tr>
		<?=statsforall($allstats,$days)?>
	</table>
	<p class="single-center-content">
		Last Browser: <?=($lastbrow!='')?$lastbrow:'Unknown'?><br />
		Last Place: <?=($lastplace!='')?$lastplace:'Unknown'?>
	</p>
</div>
<div class="single-center" style="display: <?=($tabcont=='pass')?'block':'none'?>;" id="pass-tab">
	<h1>Reset Password</h1>
	<h2><?=tabswitchlinkmaker('pass-tab',$tabs)?></h2>
	<p class="single-center-content">
		Be careful when using this - it does exactly what it advertises. Only use when the user knows what's going on! Otherwise, that's just nasty. Text fields aren't censored but still take care when entering data.
	</p>
	<form action="useradminresetpassword.php" method="post">
		<fieldset class="new-thread">
			<p class="single-center-content">
				<input type="hidden" name="user_id" value="<?=$usrid?>" />
				<input class="right" size="30" type="text" name="resetpass1" />Password:<br />
				<input class="right" size="30" type="text" name="resetpass2" />Confirm:<br />
			</p>
			<p class="single-center-content-center">
				<input type="submit" value="Reset Password" />
			</p>
		</fieldset>
	</form>
</div>
<div class="single-center" style="display: <?=($tabcont=='awards')?'block':'none'?>;" id="awards-tab">
	<h1>Awards</h1>
	<h2><?=tabswitchlinkmaker('awards-tab',$tabs)?></h2>
	<p class="single-center-content">
		Here you can give awards to the user! Tutorial and MOTM awards will be given automatically in the future but for now, use this for all awards.
	</p>
	<form action="useradminaward.php" method="post">
		<fieldset class="new-thread">
			<p class="single-center-content">
				<input type="hidden" name="user_id" value="<?=$usrid?>" />
				<select class="right" name="award">
<?
	$awtyq = mysql_query("SELECT * FROM awardtypes WHERE awardtypeID NOT IN (SELECT awardtype FROM awards WHERE awarduser = '$usrid') ORDER BY awardtypeID");
	while ($awtyr = mysql_fetch_array($awtyq))
	{
?>
					<option value="<?=$awtyr['awardtypeID']?>"><?=$awtyr['awardtypename']?></option>
<?
	}
?>
				</select>Award:
			</p>
			<p class="single-center-content-center">
				<input type="submit" value="Give!" />
			</p>
		</fieldset>
	</form>
<?
	$awardq = mysql_query("SELECT * FROM awards LEFT JOIN awardtypes ON awardtype = awardtypeID WHERE awarduser = '$usrid'");
	if ((mysql_num_rows($awardq) > 0))
	{
?>
	<h3>Current Awards</h3>
	<table class="single-row">
		<tr>
			<th>Award</th>
			<th>Awarded On</th>
		</tr>
<?
		while($awrow = mysql_fetch_array($awardq)) {
?>
		<tr><td><?=$awrow['awardtypename']?> Award</td><td style="text-align: center"><?=$awrow['awardwhen']?></td></tr>
<?
		}
?>
	</table>
<?
	}
?>
</div>
<div class="single-center" style="display: <?=($tabcont=='ban')?'block':'none'?>;" id="ban-tab">
	<h1>Banning</h1>
	<h2><?=tabswitchlinkmaker('ban-tab',$tabs)?></h2>
	<p class="single-center-content">
		This is where you can temporarily or permanently ban users. Note that a ban from here will be reversable at any time, so none of the user content is removed. To delete the account and all associated content made by the user, use the Obliterate tool. You can also edit any current bans on this user.
	</p>
<?
		$thenow = gmt("U");
		$checkforban = mysql_query("SELECT * FROM bans WHERE userID = '$usrid' AND (time > $thenow OR time < 0)");
		if (mysql_num_rows($checkforban) > 0)
		{
			$banrow = mysql_fetch_array($checkforban);
			$ban_reason = $banrow['reason'];
			$banid = $banrow['banID'];
			$ban_time = $banrow['time'];
?>
	<p class="single-center-content">
		<?=$user?> is already banned!<br />
<? 
			if ($ban_time < 0)
			{
				echo "They are banned forever.<br />Reason: $ban_reason";
			}
			else
			{
				echo "They are banned for another ".round(($ban_time-$thenow)/86400,3)." Days.<br />Reason: $ban_reason";
			}
?>
	</p>
	<h3>Edit Duration</h3>
	<form action="useradminbanedit.php?id=<?=$banid?>&amp;time" method="post">
		<fieldset class="new-thread">
			<p class="single-center-content">
				Modify ban to expire in:<br />
				<input class="right" size="10" type="text" name="bantime" value="1" />Number of Units:<br />
				<select class="right" name="banunits">
					<option value="3600">Hour(s)</option>
					<option value="86400" selected="selected">Day(s)</option>
					<option value="604800">Week(s)</option>
					<option value="-1">Forever(s)</option>
				</select>Units:
			</p>
			<p class="single-center-content-center">
				<input type="submit" value="Modify Ban Time" />
			</p>
		</fieldset>
	</form>
	<h3>Edit Reason</h3>
	<form action="useradminbanedit.php?id=<?=$banid?>&amp;reason" method="post">
		<fieldset class="new-thread">
			<p class="single-center-content">
				<input class="right" size="50" type="text" name="banreason" value="<?=$ban_reason?>" />Modify Reason for Ban:<br />
			</p>
			<p class="single-center-content-center">
				<input type="submit" value="Modify Ban Reason" />
			</p>
		</fieldset>
	</form>
	<h3>Remove Ban</h3>
	<form action="useradminbanedit.php?id=<?=$banid?>&amp;remove" method="post">
		<fieldset class="new-thread">
			<p class="single-center-content-center">
				<input type="submit" value="Remove Ban" />
			</p>
		</fieldset>
	</form>
<?
		}
		else
		{
?>
	<form action="useradminban.php?id=<?=$usrid?>" method="post">
		<fieldset class="new-thread">
			<p class="single-center-content">
				First select the duration of the ban:<br />
				<input class="right" size="10" type="text" name="bantime" value="1" />Number of Units:<br />
				<select class="right" name="banunits">
					<option value="3600">Hour(s)</option>
					<option value="86400" selected="selected">Day(s)</option>
					<option value="604800">Week(s)</option>
					<option value="-1">Forever(s)</option>
				</select>Units:<br />
				<input class="right" size="50" type="text" name="banreason" value="" />Reason for Ban:<br />
			</p>
			<p class="single-center-content-center">
				<input type="submit" value="Ban <?=$user?>" />
			</p>
		</fieldset>
	</form>
<?
		}
?>
</div>
<div class="single-center" style="display: <?=($tabcont=='oblit')?'block':'none'?>;" id="oblit-tab">
	<h1>Obliterate</h1>
	<h2><?=tabswitchlinkmaker('oblit-tab',$tabs)?></h2>
	<h3>Not functional yet.</h3>
	<p class="single-center-content">
		CRUSH KILL DESTROY
	</p>
</div>