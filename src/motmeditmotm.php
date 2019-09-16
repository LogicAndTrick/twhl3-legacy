<?
	include 'top.php';
	if (isset($lvl) && (($lvl >= 30) || ($usr == 2236)))
	{
		$motmid=mysql_real_escape_string($_GET['id']);
		$row=mysql_fetch_array(mysql_query("SELECT * FROM motm WHERE motmID = $motmid"));
?>
<div class="single-center">
	<h1>Edit MOTM #<?=$motmid?></h1>
	<h2><a href="motmedit.php">MOTM Management</a> > <a href="motmeditlist.php">MOTM List</a> > <a href="motmeditlist.php?id=<?=$motmid?>">MOTM #<?=$motmid?></a> > Modify MOTM</h2>
	<p class="single-center-content">
		<form name="modmotm" action="motmchangemotm.php?id=<?=$motmid?>" method="post">
			MOTM Map ID:<br/>
			<input type="text" name="mapid" size="20" maxlength="10" value="<?=$row["map"]?>" /> e.g. "1234"<br/>
			Month:<br/>
			<input type="text" name="date" size="20" maxlength="20" value="<?=$row["date"]?>" /> e.g. "November 2007"<br/>
			Number of Reviews:<br/>
			<input type="text" name="numreviews" size="20" maxlength="20" value="<?=$row["reviewers"]?>" /> e.g. "3"<br/><br/>
			Images:<br/>
			Thumb image:<br/>
			<input type="text" name="thumb" size="20" value="<?=$row["thumb"]?>" /> e.g. "http://img141.imageshack.us/img141/3163/demon2thumbku1.jpg"<br/>
			Big image:<br/>
			<input type="text" name="image" size="20" value="<?=$row["image"]?>" /> e.g. "http://img220.imageshack.us/img220/7335/demon2nu3.jpg"<br/><br/>
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
			<input type="submit" value="Modify MOTM">
		</form>
	</p>
</div>


<?
	}
	else fail("You are not logged in, or you do not have permission to do this.","index.php");
	include 'bottom.php';
?>