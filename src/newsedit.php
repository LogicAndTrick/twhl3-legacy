<?php

	if (isset($_SESSION['uid']) and $_SESSION['lvl']>=35)
	{
		if (isset($_GET['delete']))
		{
			
			$id=mysql_real_escape_string($_GET['delete']);
			$result = mysql_query("SELECT * FROM news LEFT JOIN users ON newscaster = userID WHERE newsID='$id'");
			if (mysql_num_rows($result) > 0)
			{
				$row = mysql_fetch_array($result);
				$postr=$row['uid'];
				$postrlvl=$row['lvl'];
				$postrid=$row['newscaster'];
				$title=$row['title'];
				$pdate=timezone($row['date'],$_SESSION['tmz'],"F jS, Y");
				$messg=$row['newsart'];
				
				$avst=getresizedavatar($row['userID'],$row['avtype'],100);
?>
<div class="single-center">	
	<h1 class="no-bottom-border">Delete - News Article #<?=$id?></h1>
	<span class="date">Posted <?=$pdate?></span>
	<h2 class="news-archive"><?=$title?></h2>
	<span class="news-info">
		<img src="<?=$avst?>" alt="<?=$postr?>" /><br />
		<a href="user.php?id=<?=$postrid?>"><?=$postr?></a><br />
		"<?=axslvl($postrlvl)?>"<br />
		<? if (isset($lvl) && $lvl >= 35) { ?><a href="editnews.php?edit=<?=$nid?>">Edit</a>/<a href="editnews.php?delete=<?=$nid?>">Delete</a><? } ?>
	</span>
	<p class="news-content">
		<?=comment_format($messg)?>
	</p>
</div>
<div class="single-center" id="gap-fix-bottom">
	<h1>Confirm Delete</h1>
	<p class="single-center-content">
		Are you sure you want to delete this news article?
	</p>
	<form action="newsdelete.php?id=<?=$id?>" method="post">
		<fieldset class="new-thread">
			<input class="right" id="post-thread" value="Delete" type="submit" />
		</fieldset>
	</form>
</div>
<?
			}
		}
		elseif (isset($_GET['edit']))
		{			
			$id=mysql_real_escape_string($_GET['edit']);
			$result = mysql_query("SELECT * FROM news WHERE newsID='$id'");
			if (mysql_num_rows($result) > 0)
			{
				$row = mysql_fetch_array($result);
				$postr=$row['uid'];
				$title=$row['title'];
				$pdate1=$row['date'];
				$pdate=date(F,$pdate1) . " " . date(j,$pdate1) .  date(S,$pdate1) . "&#44; " . date(Y,$pdate1);
				$messg=$row['newsart'];
			
?>
<div class="single-center">
	<h1>Editing a News Post</h1>
	<p class="single-center-content">
		From here, you can edit the content of a news post. You can change the ID of who posted by putting a new ID number. If you want to use the current date and time, simply tick the box. If you're not changing this info, leave the boxes blank.
	</p>	
</div>	
<div class="single-center" id="gap-fix">
	<h1>Edit - News Post #<?=$id?> by <?=$postr?></h1>
	<form action="newschange.php?id=<?=$id?>" method="post">
		<fieldset class="new-thread">
			<p class="single-center-content"> 
				<input class="right" type="text" name="title" value="<?=$title?>" />Title:
			</p>
			<fieldset style="text-align: center;">
				<textarea rows="10" cols="76" name="newstext"><?=$messg?></textarea>
			</fieldset>
			<p class="single-center-content">
				<input class="right" type="text" size="14" name="userid" style="margin-left: 5px;" /> Change User ID?
			</p>
			<p class="single-center-content">
				<input class="right" type="checkbox" name="newdate" style="margin-left: 5px;" /> Refresh post date?
			</p>
			<input class="right" id="post-thread" value="Edit" type="submit" />
		</fieldset>
	</form>
</div>
<?	
			}
		}
		else
		{
			echo 'No news post selected';
		}
	}
?>