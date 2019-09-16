<?php

	$id=mysql_real_escape_string($_GET['editthread']);
	$result = mysql_query("SELECT threads.*, users.uid, forumcats.name AS fname FROM threads LEFT JOIN forumcats ON threads.forumcat = forumcats.forumID LEFT JOIN users ON ownerid = userID WHERE threadID='$id'");

	if (isset($_GET['editthread']) && ($_GET['editthread']!="") && is_numeric($_GET['editthread']) && 
	(mysql_num_rows($result) > 0) && isset($_SESSION['uid']) && $_SESSION['lvl']>=35)
	{
		$row = mysql_fetch_array($result);
		
		$cat=$row['forumcat'];
		$thname=$row['name'];
		$fname=$row['fname'];
		$owner=$row['ownerid'];
		$usernm=$row['uid'];
		$open=$row['prop_open'];
		$sticky=$row['prop_sticky'];
		
		$ptext=post_format($ptext);
		
		$moveq = mysql_query("SELECT * from forumcats WHERE accesslevel >= 0 ORDER BY forumID ASC");
		
		?>
		<div class="single-center">
				<h1>Moderate thread</h1>
				<p class="single-center-content">
					Welcome to the TWHL Forum Moderation page. From here, you can completely moderate a thread, be it deletion, moving or other options. Generally speaking, you probably won't need to adjust the original author ID, but it's there if needed.
				</p>	
			</div>
			<div class="single-center" id="gap-fix">
				<h1>Edit - Thread <?=$id?> by <?=$usernm?></h1>
				<h2><a href="forums.php">Forums</a> &gt; <a href="forums.php?id=<?=$cat?>"><?=$fname?></a> &gt; <a href="forums.php?thread=<?=$id?>"><?=$thname?></a></h2>
				<form action="forumchangethread.php?id=<?=$id?>" method="post">
					<fieldset class="edit-thread">
<p class="single-center-content">
						<input class="right" name="name" maxlength="35" type="text" value="<?=$thname?>"/>Thread Name<br />
						<input class="right" name="newid" size="4" type="text" value="<?=$owner?>"/>Author ID<br />
						<select class="right" name="newforum">
						<? while ($move = mysql_fetch_array($moveq)) { ?>
								<option value="<?=$move['forumID']?>"<?=($move['forumID']==$cat)?' selected="selected"':''?>><?=$move['name']?></option>
						<? } ?>
						</select>Forum<br />
						<input class="check" name="sticky" type="checkbox"<?=($sticky==1)?' checked="checked"':''?> />Sticky<br />
						<input class="check" name="closed" type="checkbox"<?=($open==0)?' checked="checked"':''?> />Closed<br />
						<input class="center" type="submit" value="Edit" />
</p>
					</fieldset>
				</form>
			</div>
			<div class="single-center" style="margin-top: 130px;">
				<h1>Delete - Thread <?=$id?> by <?=$usernm?></h1>
				<h2><a href="forums.php">Forums</a> &gt; <a href="forums.php?id=<?=$cat?>"><?=$fname?></a></h2>
				<p class="single-center-content" id="center">
					Thread: <?=$thname?>
				</p>	
				<form action="forumdeletethread.php?id=<?=$id?>" method="post">
					<fieldset style="margin: 10px auto; padding: 10px; text-align: center; width:300px; border: 3px solid red;">
						<input style="font-weight: bold; font-size: 26px;" class="center" type="submit" value="Delete" />
					</fieldset>
				</form>
			</div>

	<?
		
	}
	else
	{
		if (!isset($_SESSION['lvl']) || ($_SESSION['lvl']<35))
		{
			$problem = "You don't have permission to view this page.";
			$back = "forums.php";
		}
		elseif (!isset($_GET['editthread']) || ($_GET['editthread']=="") || !is_numeric($_GET['editthread']))
		{
			$problem = "There is no thread specified.";
			$back = "forums.php";
		}
		else
		{
			$problem = "The specified thread does not exist.";
			$back = "forums.php";
		}
		include 'failure.php';
	}
?>