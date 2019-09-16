<?
	$getpm = mysql_real_escape_string($_GET['view']);
	
	if (!(isset($usr) && ($usr != ""))) fail("You must be logged in to do this.","index.php");
	
	if ($lvl >= 40) $pmq = mysql_query("SELECT * FROM pminbox LEFT JOIN users ON pmfrom = userID WHERE pmID = '$getpm'");
	else $pmq = mysql_query("SELECT * FROM pminbox LEFT JOIN users ON pmfrom = userID WHERE pmID = '$getpm' AND pmto = '$usr'");
	
	if (mysql_num_rows($pmq) == 0) fail("Message not found.","privmsg.php");
	
		$pmr = mysql_fetch_array($pmq);
		if ($pmr['isnew']==1 && $pmr['pmto']==$usr) mysql_query("UPDATE pminbox SET isnew = 0 WHERE pmID = '$getpm'");
		$newsubject = $pmr['pmsubject'];
		$newsubject = "Re: ".((strtolower(substr($newsubject,0,3)) == "re:")?trim(substr($newsubject,3)):$newsubject);
		
	$inq = mysql_query("SELECT * FROM pminbox LEFT JOIN users ON pmfrom = userID WHERE pmto = '$usr' AND isnew >= 0 ORDER BY pmtime DESC");
	
	if ($pmr['pmto']!=$usr)
	{
		$touserq = mysql_query("SELECT * FROM users WHERE userID = '" . $pmr['pmto'] . "'");
		$touserr = mysql_fetch_array($touserq);
		$touser = $touserr['uid'];
	}
	
	$num_inbox = mysql_num_rows($inq);
?>
<div class="single-center">
	<h1><?=$pmr['pmsubject']?></h1>
	<h2><a href="privmsg.php">Back to Inbox</a> | <a href="privmsg.php?send">Compose New Message</a> | <a href="privdelete.php?id=<?=$getpm?>">[D</a> | <a href="privarchive.php?id=<?=$getpm?>">A]</a></h2>
<?
		$avatar = getresizedavatar($pmr['userID'],$pmr['avtype'],100);
		$date = timezone($pmr['pmtime'],$_SESSION['tmz'],"jS F Y");
		$smalldate = timezone($pmr['pmtime'],$_SESSION['tmz'],"H:i");
?>
	<div class="private-message-container">
		<span class="right-avatar">
			<? if ($pmr['pmfrom']!=0) { ?><img src="<?=$avatar?>" alt="avatar" /><? } else { ?><img src="gfx/avatars/001.gif" alt="avatar" /><? } ?>
		</span>
		<p class="right-info">
			&nbsp;<br />
			From <? if ($pmr['pmfrom']!=0) { ?><a href="user.php?id=<?=$pmr['userID']?>"><?=$pmr['uid']?></a><? } else { ?>Anonymous<? } ?><br />
			<? if ($pmr['pmto']!=$usr) { ?>To <a href="user.php?id=<?=$pmr['pmto']?>"><?=$touser?></a><br /><? } ?>
			<strong><?=$date?></strong><br />
			<?=$smalldate?><br />
			&nbsp;
		</p>
		<div class="message">
			<?=post_format($pmr['pmtext'])?>
		</div>
	</div>
</div>
<div class="single-center" id="gap-fix-bottom">
	<h1>Send a Reply</h1>
<? 
	if ($pmr['pmfrom']!=0)
	{
		if ($num_inbox <= 200 || (isset($lvl) && $lvl >= 20))
		{
?>
	<form method="post" action="privsend.php">
		<fieldset class="new-thread">
			<p class="single-center-content">
				<input class="right" size="40" type="text" name="pmsub" value="<?=$newsubject?>" />Subject:<br />
				<input type="hidden" name="pmto" value="<?=$pmr['uid']?>" />
			</p>
			<p class="single-center-content">
				<textarea rows="10" cols="76" name="pmtext"><?="\n\n\n[quote]".$pmr['pmtext']."[/quote]"?></textarea>
			</p>
			<p class="single-center-content-center">
				<input type="submit" name="pmprev" value="Preview" />
				<input type="submit" name="pmpost" value="Post" />
			</p>
		</fieldset>
	</form>
<?
		}
		else
		{
?>
			<p class="single-center-content">
				You cannot send a message until you have 200 or less messages in your inbox.
			</p>
<?
		}
	}
	else
	{
?>
	<p class="single-center-content">
		You cannot reply to anonymous PM's.
	</p>
<?
	}
?>
</div>