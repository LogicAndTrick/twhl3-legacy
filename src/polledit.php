<?
	include 'top.php';
	if (isset($lvl) && $lvl >= 40)
	{
		if (isset($_GET['id']))
		{
			$getpoll = mysql_real_escape_string($_GET['id']);
			$poleq = mysql_query("SELECT * FROM polls WHERE pollID = '$getpoll' AND isactive = 1");
			if (mysql_num_rows($poleq) > 0)
			{
				$poler = mysql_fetch_array($poleq);
?>
<div class="single-center">
	<h1>Poll Edit</h1>
	<h2><a href="admin.php">Admin Panel</a> &gt; <a href="polledit.php">Polls</a> &gt; Edit Poll</h2>
	<form method="post" action="pollchange.php?id=<?=$getpoll?>">
		<fieldset class="new-thread">
			<p class="single-center-content">
				<input class="right" size="40" type="text" name="polltitle" value="<?=$poler['polltitle']?>" />Title:<br />
				<input class="right" size="40" type="text" name="pollsubtitle" value="<?=$poler['pollsubtitle']?>" />Subtitle:<br />
			</p>
			<p class="single-center-content-center">
				<input type="submit" value="Edit" />
			</p>
		</fieldset>
	</form>
<?
				$poliq = mysql_query("SELECT * FROM pollitems WHERE itempoll = '$getpoll' ORDER BY itemID ASC");
				if (mysql_num_rows($poliq) > 0)
				{
?>
	<p class="single-center-content">
		Poll Items:
	</p>
	<p class="single-center-content">
<?
					while ($polir = mysql_fetch_array($poliq))
					{
?>
		<?=$polir['item']?> (<a href="polledit.php?item=<?=$polir['itemID']?>">Edit</a>)<br />
<?
					}
?>
	</p>
<?
				}
?>
	<p class="single-center-content">
		<a href="pollclose.php?id=<?=$getpoll?>">Close Poll</a>
	</p>
</div>
<?
			}
		}
		elseif (isset($_GET['item']))
		{
			$getitem = mysql_real_escape_string($_GET['item']);
			$poliq = mysql_query("SELECT * FROM pollitems WHERE itemID = '$getitem'");
			if (mysql_num_rows($poliq) > 0)
			{
				$polir = mysql_fetch_array($poliq);
?>
<div class="single-center">
	<h1>Edit Poll Item</h1>
	<h2><a href="admin.php">Admin Panel</a> &gt; <a href="polledit.php">Polls</a> &gt; Edit Poll Item</h2>
	<form method="post" action="pollitemchange.php?id=<?=$getitem?>">
		<fieldset class="new-thread">
			<p class="single-center-content">
				<input class="right" size="40" type="text" name="itemname" value="<?=$polir['item']?>" />Item:<br />
			</p>
			<p class="single-center-content-center">
				<input type="submit" value="Edit" />
			</p>
		</fieldset>
	</form>
</div>
<?
			}
		}
		elseif (isset($_GET['new']))
		{
?>
<div class="single-center">
	<h1>Add New Poll</h1>
	<h2><a href="admin.php">Admin Panel</a> &gt; <a href="polledit.php">Polls</a> &gt; New Poll</h2>
	<form method="post" action="polladd.php">
		<fieldset class="new-thread">
			<p class="single-center-content">
				<input class="right" size="40" type="text" name="polltitle" />Title:<br />
				<input class="right" size="40" type="text" name="pollsubtitle" />Subtitle:
			</p>
			<p class="single-center-content">
				<input class="right" size="40" type="text" name="pollitem1" />Item 1:<br />
				<input class="right" size="40" type="text" name="pollitem2" />Item 2:<br />
				<input class="right" size="40" type="text" name="pollitem3" />Item 3:<br />
				<input class="right" size="40" type="text" name="pollitem4" />Item 4:<br />
				<input class="right" size="40" type="text" name="pollitem5" />Item 5:<br />
				<input class="right" size="40" type="text" name="pollitem6" />Item 6:<br />
			</p>
			<p class="single-center-content-center">
				<input type="submit" value="Post" />
			</p>
		</fieldset>
	</form>
</div>
<?
		}
		else
		{
?>
<div class="single-center">
	<h1>Poll Management</h1>
	<h2><a href="admin.php">Admin Panel</a> &gt; Polls</h2>
	<p class="single-center-content">
		From here you can choose to edit a current poll, close a running poll, or create a new poll. You cannot open an old poll that has been closed already.
	</p>
	<p class="single-center-content">
		<a href="polledit.php?new">Create a New Poll</a>
	</p>
<?
			$polllq = mysql_query("SELECT * FROM polls WHERE isactive = 1 ORDER BY pollID DESC");
			if (mysql_num_rows($polllq) > 0)
			{
				$polllr = mysql_fetch_array($polllq);
?>
	<p class="single-center-content">
		<a href="polledit.php?id=<?=$polllr['pollID']?>">Edit current poll (<?=$polllr['polltitle']?>)</a>
	</p>
<?
			}
?>
</div>
<?
		}
	}
	else fail("You aren't logged in, or you don't have permission to do this.","index.php");
	include 'bottom.php';
?>