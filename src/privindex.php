<?
	if (!(isset($usr) && ($usr != ""))) fail("You must be logged in to do this.","index.php");

	$currentpage = "in";
	if (isset($_GET['send']))
	{
		$currentpage = "send";
		$sendto = mysql_real_escape_string($_GET['send']);
		$seq = mysql_query("SELECT * FROM users WHERE userID = '$sendto'");
		if (mysql_num_rows($seq) > 0)
		{
			$ser = mysql_fetch_array($seq);
			$senduid = $ser['uid'];
		}
		else $senduid = "";
	}
	
	$inq = mysql_query("SELECT * FROM pminbox LEFT JOIN users ON pmfrom = userID WHERE pmto = '$usr' AND isnew >= 0 ORDER BY pmtime DESC");
	$arq = mysql_query("SELECT * FROM pminbox LEFT JOIN users ON pmfrom = userID WHERE pmto = '$usr' AND isnew = '-1' ORDER BY pmtime DESC");
	
	$num_inbox = mysql_num_rows($inq);
?>


<div class="single-center" style="margin-bottom: 0;">
	<h1>Private Messages</h1>
	<h2><a href="javascript:tabswitcher(new Array('inbox-tab','archive-tab','send-tab'))">Inbox</a> | <a href="javascript:tabswitcher(new Array('archive-tab','inbox-tab','send-tab'))">Archived</a> | <a href="javascript:tabswitcher(new Array('send-tab','inbox-tab','archive-tab'))">Create a Message</a></h2>
	<span class="left-control-image">
		<img src="images/envelope_large.png" alt="large envelope" />
	</span>
	<p class="single-center-content"> 
		You can use this page to send messages to particular other users of this site rather than contacting them via e-mail, etc., or posting threads directed at individuals on the forums (now frowned upon).
	</p>
	<p class="single-center-content"> 
		Only store what you really need to. If you wish to conduct lengthy correspondence, it would probably make more sense to use e-mail.
	</p>
	<p class="single-center-content"> 
		Note that there is a limit of 200 private messages you can have in your inbox, until you will not be able to send messages - only recieve. To restore the ability to send messages, simply delete messages from your inbox until you have 200 or less.
	</p>
</div>	
<div class="single-center" style="display: <?=($currentpage=='in')?'block':'none'?>" id="inbox-tab">
	<h1>Your Messages</h1>
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
		You have no Private Messages.
	</p>
<?
	}
?>
</div>
<div class="single-center" style="display: none;" id="archive-tab">
	<h1>Archived Messages</h1>
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
		You have no archived Private Messages.
	</p>
<?
	}
?>
</div>
<div class="single-center" style="display: <?=($currentpage=='send')?'block':'none'?>" id="send-tab">
	<h1>Send a Message</h1>
<?
	if ($num_inbox <= 200 || (isset($lvl) && $lvl >= 20))
	{
?>
	<form method="post" action="privsend.php">
		<fieldset class="new-thread">
			<p class="single-center-content">
				<input class="right" size="40" type="text" name="pmto" value="<?=$senduid?>" />To:<br />
				<input class="right" size="40" type="text" name="pmsub" value="" />Subject:
			</p>
			<p class="single-center-content">
				<textarea rows="10" cols="76" name="pmtext"></textarea>
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
?>
</div>