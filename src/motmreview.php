<?
	include 'top.php';
	if (isset($lvl) && (($lvl >= 30) || ($usr == 2236)))
	{
		$revid=mysql_real_escape_string($_GET['id']);
		$row=mysql_fetch_array(mysql_query("SELECT * FROM motmreviews WHERE reviewID = $revid"));
		$motmid = $row['motm'];
?>
<div class="single-center">
	<h1>MOTM Management</h1>
	<h2><a href="motmedit.php">MOTM Management</a> > <a href="motmeditlist.php">MOTM List</a> > <a href="motmeditlist.php?id=<?=$motmid?>">MOTM #<?=$motmid?></a> > Modify Review</h2>
	<p class="single-center-content">
		<form name="newmotmrev" action="motmchangereview.php?id=<?=$_GET['id']?>" method="post">
			Reviewer ID:<br/>
			<input type="text" name="revid" size="20" maxlength="10" value="<?=$row["reviewer"]?>" /> e.g. "1983"<br/>
			Find the user ID by visiting the user's map and clicking their name<br/>
			The ID will be in the URL (userprofile.php?id=1983)<br/>
			you can also get it from comments, shoutbox, and other places (not forums)<br/>
			ps: look at the link on the user's profile to send them a PM, its there too.<br/>
			Ratings:<br/>
			Architecture:<br/>
			<input type="text" name="arch" size="20" maxlength="3" value="<?=$row["arch"]?>" /> maximum one decimal place, e.g. "8","9.6"<br/>
			Texturing:<br/>
			<input type="text" name="tex" size="20" maxlength="3" value="<?=$row["tex"]?>" /> maximum one decimal place, e.g. "8","9.6"<br/>
			Ambience:<br/>
			<input type="text" name="amb" size="20" maxlength="3" value="<?=$row["amb"]?>" /> maximum one decimal place, e.g. "8","9.6"<br/>
			Lighting:<br/>
			<input type="text" name="light" size="20" maxlength="3" value="<?=$row["light"]?>" /> maximum one decimal place, e.g. "8","9.6"<br/>
			Gameplay:<br/>
			<input type="text" name="game" size="20" maxlength="3" value="<?=$row["game"]?>" /> maximum one decimal place, e.g. "8","9.6"<br/>
			Total:<br/>
			<input type="text" name="total" size="20" maxlength="4" value="<?=$row["total"]?>" /> maximum one decimal place, is a percentage. don't include % symbol, e.g. "88","96.7"<br/><br/>
			Review content:<br/>
			<textarea name="content" rows="20" cols="80" style="font-family: sans-serif; font-size: 14px;"><?=str_replace("<br />","\n",$row["content"])?></textarea><br/><br/>
			bbcode:<br/>
			[sub] - subheading.<br/>
			e.g. [sub]Exclusive Video Review[/sub] = <font color="#FF9421"><b>Exclusive Video Review</b></font><br/><br/>
			[youtube] - embeds youtube. grab the "value" from the youtube embed code.<br/>
			e.g. [youtube]http://www.youtube.com/v/xJ9-d0L_mrg&amp;<br/>
			rel=1&amp;color1=0xe1600f&amp;color2=0xfebd01&amp;border=0[/youtube]<br/><br/>
			(obviously don't break it up, its like that to stop the site from stretching)<br/><br/>
			[b],[i],[u]<br/><br/>
			<input type="submit" value="Modify Review">
		</form>
		<br /><br /><br />
		Once you click this, the review will instantly be deleted. Watch out!<br />
		<a href="motmreviewdelete.php?id=<?=$revid?>">Click here to delete this review.</a>
	</p>
</div>
<?
	}
	else fail("You are not logged in, or you do not have permission to do this.","index.php");
	include 'bottom.php';
?>