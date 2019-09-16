<?

die();

$getid=mysql_real_escape_string($_GET['tut']);

$result = mysql_query("SELECT * FROM tutorials WHERE tutorialID = $getid");
$row = mysql_fetch_array($result);

$result2 = mysql_query("SELECT * FROM tutorialpages WHERE tutorialid = '$getid' AND page = '1'");
$row2 = mysql_fetch_array($result2);

?>
<div class="single-center">
	<h1><?=$row['name']?></h1>
	<?=tutorial_format($row2['content'])?>
</div>
<div class="single-center" id="gap-fix-bottom">
	<h1>ADD AN IMAGE</h1>
	<form action="tutdoimage3k.php?id=<?=$getid?>" method="post">
		<fieldset class="new-thread">
			<fieldset style="text-align: center;">
				<p class="single-center-content">
					<?
					$result3 = mysql_query("SELECT * FROM tutorialpics WHERE tutID = $getid");
					while ($row3 = mysql_fetch_array($result3)) {
					?>
					<a href="javascript:smilie('[img=<?=$row3['piclink']?>]caption[/img]')" title="<?=$row3['piclink']?>"><img style="max-width:100px;" src="tutpics/<?=$row3['piclink']?>" alt="tutorial pic" /></a>
					<?
					}
					?>
				</p>
				Image is at: http://twhl.info/tutpics/<input name="picname" type="text" /><br />
			<input style="margin: 10px;" value="Add" type="submit" />
			</fieldset>
		</fieldset>
	</form>
</div>
<? if (($row['example'] != '') && ($row['examplesize'] == 0)) { ?>
<div class="single-center" id="gap-fix-bottom">
	<h1>EXAMPLE MAP DETAILS</h1>
	<form action="tutdoexample3k.php?id=<?=$getid?>" method="post">
		<fieldset class="new-thread">
			<fieldset style="text-align: center;">
				Example map link: <a href="http://twhl.info/tutorialdl/<?=$row['example']?>"><?=$row['example']?></a><br /><br />
				Download it and enter these details in:<br /><br />
				file size (bytes) - example: "4563212"<br /><input name="filesize" type="text" /><br /><br />
				file contents - example: "lol.jpg, pie.vmf, zing.txt"<br /><input name="contents" type="text" /><br /><br />
				file notes (blank if no notes) - example: "hammer tries to shoot you on load, so do a barrel roll to avoid it"<br /><input name="notes" type="text" /><br /><br />
			<input style="margin: 10px;" value="Inform!" type="submit" />
			</fieldset>
		</fieldset>
	</form>
</div>
<? } ?>
<div class="single-center" id="gap-fix-bottom">
	<h1>EDIT</h1>
	<form action="tutdoedit3k.php?id=<?=$getid?>" method="post">
		<fieldset class="new-thread">
			<fieldset style="text-align: center;">
				<textarea id="newposttext" name="tuttext" rows="100" cols="76"><?=$row2['content']?></textarea>
			<input style="margin: 10px;" value="Edit" type="submit" />
			</fieldset>
		</fieldset>
	</form>
</div>
<div class="single-center" id="gap-fix-bottom">
	<h1>FINISHED</h1>
	<form action="tutdofinish3k.php?id=<?=$getid?>" method="post">
		<fieldset class="new-thread">
			<fieldset style="text-align: center;">
				Click when finished: <br /><br />
				<input style="margin: 10px;" value="lol" type="submit" />
			</fieldset>
		</fieldset>
	</form>
</div>
<?


?>