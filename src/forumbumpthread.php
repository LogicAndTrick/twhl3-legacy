<?php
	
	$thd=mysql_real_escape_string($_GET['threadbump']);
	$result = mysql_query("SELECT threads.*, users.uid, forumcats.name AS fname FROM threads LEFT JOIN forumcats ON threads.forumcat = forumcats.forumID LEFT JOIN users ON ownerid = userID WHERE threadID='$thd' AND accesslevel < $lvl");
	
	if (isset($thd) && is_numeric($thd) && trim($thd)!="" && isset($_SESSION['uid']) && (mysql_num_rows($result) > 0))
	{
		$row = mysql_fetch_array($result);
		$cat=$row['forumcat'];
		$thname=$row['name'];
		$fname=$row['fname'];
	?>
	<div class="single-center">
		<h1>Requesting a Thread Bump</h1>
		<p class="single-center-content">
			Here you can submit a request to bump a thread that has been inactive longer than three months. The content will be assessed by an admin to see whether the bump is nescessary. You will be notified of the result with a User Alert.
		</p>	
	</div>	
	<div class="single-center" id="gap-fix">

		<h1>Bump - Thread <?=$thd?></h1>
		<h2><a href="forums.php">Forums</a> &gt; <a href="forums.php?id=<?=$cat?>"><?=$fname?></a> &gt; <a href="forums.php?thread=<?=$thd?>"><?=$thname?></a></h2>
		<form action="forumthreadbump.php?id=<?=$thd?>" method="post">
			<div class="smilies" id="smiley-toggle"><a href="javascript:togglesmilies()">[Show Smilies]</a></div>
			<div class="smilies" id="smiley-content"><? include 'smilies.php'; ?></div>
			<fieldset class="new-thread">
			<div class="smilies" id="bb-toggle"><a href="javascript:toggle('bb')">[Show BBCode]</a></div>
			<div class="smilies" id="bb-content">
				<? include 'bbcode.php'; ?>
			</div>
				<fieldset style="text-align: center;">
					<textarea id="newposttext" name="posttxt" rows="10" cols="76"></textarea>
				</fieldset>
				<? if ($_SESSION['lvl'] > 4) { ?>
				<div class="smilies" id="modbb-toggle"><a href="javascript:toggle('modbb')">[Show Mod BBCode]</a></div>
				<div class="smilies" id="modbb-content">
					<input value="purple" id="purple" class="bbcode" type="button" onclick="javascript:bbcode('purple')" onmouseover="javascript:over('purple')" onmouseout="javascript:out('purple')" />
					<input value="m" id="mod" class="bbcode" type="button" onclick="javascript:bbcode('mod')" onmouseover="javascript:over('mod')" onmouseout="javascript:out('mod')" />
					<input value="fail" id="fail" class="bbcode" type="button" onclick="javascript:youfail()" onmouseover="javascript:over('fail')" onmouseout="javascript:out('fail')" />
				</div>
				<? } ?>
				<input class="right" id="post-thread" type="submit" value="Bump" />
			</fieldset>
		</form>
	</div>
	<?
	}
	else
	{
		if (!isset($_SESSION['lvl']) || ($_SESSION['lvl']==""))
		{
			$problem = "You don't seem to be logged in.";
			$back = "forums.php";
		}
		elseif (!isset($thd) || !is_numeric($thd) || !trim($thd)!="")
		{
			$problem = "There is no action specified.";
			$back = "forums.php";
		}
		else
		{
			$problem = "This thread doesn't exist.";
			$back = "forums.php";
		}
		include 'failure.php';
	}
?>