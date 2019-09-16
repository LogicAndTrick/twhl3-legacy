<?
	if (!(isset($usr) && ($usr != ""))) fail("You must be logged in to view this page.","index.php");
	$peditq = mysql_query("SELECT * FROM users WHERE userID = '$usr'");
	if (mysql_num_rows($peditq) == 0) fail("You don't exist.","index.php");
	$trackq = mysql_query("SELECT * FROM threadtracking LEFT JOIN threads ON trackthread = threadID WHERE trackuser = '$usr' ORDER BY trackID DESC");
	$alertq = mysql_query("SELECT * FROM alertuser LEFT JOIN alerttypes ON alerttype = alerttypeID LEFT JOIN users ON alerter = userID WHERE alertuser = '$usr' ORDER BY alertdate DESC");
	
	$tabcont = 'alerts';
	if (isset($_GET['alerts'])) $tabcont = 'alerts';
	elseif (isset($_GET['tracking'])) $tabcont = 'tracking';
	elseif (isset($_GET['journal'])) $tabcont = 'journal';
	elseif (isset($_GET['edit'])) $tabcont = 'edit';
	elseif (isset($_GET['avatar'])) $tabcont = 'avatar';
?>
<div class="single-center" style="margin-bottom: 0;">
	<h1>Your Control Panel</h1>
	<h2><a href="user.php?id=<?=$usr?>">View Your Profile</a> | <a href="forums.php">Forums</a> | <a href="vault.php">Map Vault</a></h2> 	
	<span class="left-control-image">
		<img src="images/spanner_large.png" alt="large spanner" />
	</span>
	<p class="single-center-content">
		Welcome to your Control Panel! This is the central hub for thread tracking, Journals and other important site features. Any threads you subscribe to will show up here. You're able to manage what you're tracking and stop tracking them whenever you wish. Changing over to the Journals tab will allow you to post and edit Journal entries. You're also able to edit most aspects of your profile. This will show you all TWHL User Alerts, including all correspondance for Tutorial Proposals and other important info.
	</p>	
</div>
<div class="single-center" style="display: <?=($tabcont=='alerts')?'block':'none'?>;" id="alert-tab">
	<h1>TWHL User Alerts</h1>
	<h2>TWHL User Alerts | <a href="javascript:tabswitcher(new Array('track-tab','alert-tab','journal-tab','edit-tab','avatar-tab'))">Thread Tracking</a> | <a href="javascript:tabswitcher(new Array('journal-tab','track-tab','alert-tab','edit-tab','avatar-tab'))">Your Journals</a> | <a href="javascript:tabswitcher(new Array('edit-tab','track-tab','alert-tab','journal-tab','avatar-tab'))">Edit Your Profile</a> | <a href="javascript:tabswitcher(new Array('avatar-tab','journal-tab','track-tab','alert-tab','edit-tab'))">Change Avatar</a></h2>
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
		You don't have any alerts.
	</p>
<?
	}
?>
</div>
<div class="single-center" style="display: <?=($tabcont=='tracking')?'block':'none'?>;" id="track-tab">
	<h1>Thread Tracking</h1>
	<h2><a href="javascript:tabswitcher(new Array('alert-tab','track-tab','journal-tab','edit-tab','avatar-tab'))">TWHL User Alerts</a> | Thread Tracking | <a href="javascript:tabswitcher(new Array('journal-tab','track-tab','alert-tab','edit-tab','avatar-tab'))">Your Journals</a> | <a href="javascript:tabswitcher(new Array('edit-tab','track-tab','alert-tab','journal-tab','avatar-tab'))">Edit Your Profile</a> | <a href="javascript:tabswitcher(new Array('avatar-tab','journal-tab','track-tab','alert-tab','edit-tab'))">Change Avatar</a></h2>
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
		You aren't tracking any threads.
	</p>
<?
		}
?>
</div>
<div class="single-center" style="display: <?=($tabcont=='journal')?'block':'none'?>;" id="journal-tab">
	<h1>Journals</h1>
	<h2><a href="javascript:tabswitcher(new Array('alert-tab','track-tab','journal-tab','edit-tab','avatar-tab'))">TWHL User Alerts</a> | <a href="javascript:tabswitcher(new Array('track-tab','alert-tab','journal-tab','edit-tab','avatar-tab'))">Thread Tracking</a> | Your Journals | <a href="javascript:tabswitcher(new Array('edit-tab','track-tab','alert-tab','journal-tab','avatar-tab'))">Edit Your Profile</a> | <a href="javascript:tabswitcher(new Array('avatar-tab','journal-tab','track-tab','alert-tab','edit-tab'))">Change Avatar</a></h2>
	<p class="single-center-content">
		Use this form to Post a new journal to your profile.
	</p>
	<form action="useraddjournal.php" method="post">
		<fieldset class="new-thread">
			<p class="single-center-content">
				<textarea rows="10" cols="76" name="journtext"></textarea>
			</p>
			<p class="single-center-content-center">
				<input type="submit" value="Post" />
			</p>
		</fieldset>
	</form>
</div>
<div class="single-center" style="display: <?=($tabcont=='edit')?'block':'none'?>;" id="edit-tab">
	<h1>Edit profile</h1>
	<h2><a href="javascript:tabswitcher(new Array('alert-tab','track-tab','journal-tab','edit-tab','avatar-tab'))">TWHL User Alerts</a> | <a href="javascript:tabswitcher(new Array('track-tab','alert-tab','journal-tab','edit-tab','avatar-tab'))">Thread Tracking</a> | <a href="javascript:tabswitcher(new Array('journal-tab','track-tab','alert-tab','edit-tab','avatar-tab'))">Your Journals</a> | Edit Your Profile | <a href="javascript:tabswitcher(new Array('avatar-tab','journal-tab','track-tab','alert-tab','edit-tab'))">Change Avatar</a></h2>
<?
		$pdr = mysql_fetch_array($peditq);
		$avat = $pdr['avtype'];
?>
	<form action="userprofileedit.php" method="post" enctype="multipart/form-data">
		<fieldset class="new-thread">
			<p class="single-center-content">
				<input class="right" size="30" type="password" name="pass1" />New Password (keep blank to retain current password):<br />
				<input class="right" size="30" type="password" name="pass2" />Verify New Password:<br />
<?
		if ($pdr['allowtitle'] > 0 || $pdr['lvl'] >= 20)
		{
?>
				<input class="right" style="margin: 5px" type="checkbox" name="usetitle"<?=($pdr['usetitle']==1)?' checked="checked"':''?> /><span style="float:right;">Use custom title</span><br /><br />
				<input class="right" size="30" type="text" name="title" value="<?=$pdr['title']?>" />Custom Title:<br />
<?
		}
?>
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
	<h2><a href="javascript:tabswitcher(new Array('alert-tab','track-tab','journal-tab','edit-tab','avatar-tab'))">TWHL User Alerts</a> | <a href="javascript:tabswitcher(new Array('track-tab','alert-tab','journal-tab','edit-tab','avatar-tab'))">Thread Tracking</a> | <a href="javascript:tabswitcher(new Array('journal-tab','track-tab','alert-tab','edit-tab','avatar-tab'))">Your Journals</a> | <a href="javascript:tabswitcher(new Array('edit-tab','track-tab','alert-tab','journal-tab','avatar-tab'))">Edit Your Profile</a> | Change Avatar</h2>
	<p class="single-center-content">
		Pick an avatar from the list below, or upload an image to be your avatar. Your image will be automatically resized. Images which are inappropriate will be deleted and may result in a ban from the site.
	</p>
	<form action="userchangeavatar.php" method="post" enctype="multipart/form-data">
		<fieldset class="new-thread">
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
				<input type="radio" name="avselect" value="-1" <?=($avat<0)?'checked="checked" ':''?>/> Image Upload (.png or .jpg formats)<br />
				<input type="file" name="avup" size="40" />
			</p>
			<p class="single-center-content-center">
				<input type="submit" value="Change Avatar" />
			</p>
		</fieldset>
	</form>
</div>