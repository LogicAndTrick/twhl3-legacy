<?
	$getcomp = mysql_real_escape_string($_GET['submit']);
	$thenow = gmt("U");
	$compq = mysql_query("SELECT * FROM compos WHERE compID = '$getcomp' AND compclose > '$thenow' AND compopen > 0 AND compopen < $thenow");
	
	if (!(isset($usr) && ($usr != ""))) fail("You must be logged in to enter a competition.","competitions.php?comp=$getcomp");
	if (mysql_num_rows($compq) == 0) fail("The competition you are trying to enter is not open or doesn't exist.","competitions.php");
	
	$compr = mysql_fetch_array($compq);
?>
<div class="single-center">
	<h1>Enter a Competition</h1>
	<h2><a href="competitions.php">Competitions</a> &gt; <a href="competitions.php?comp=<?=$getcomp?>"><?=$compr['compname']?></a> &gt; Submit Entry</h2>
	<p class="single-center-content"> 
		Welcome to the submission page for Competition #<?=$getcomp?>, <?=$compr['compname']?>. Please ensure you've followed all the guidelines from the <a href="competitions.php?comp=<?=$getcomp?>">brief</a> before submitting your map. Maps over 2MB in size will need to be uploaded elsewhere and linked here.
	</p>	
</div>
<div class="single-center" id="gap-fix">
	<h1>Enter "<?=$compr['compname']?>"</h1>
	<form action="compaddentry.php?id=<?=$getcomp?>" method="post" enctype="multipart/form-data">
		<fieldset class="new-thread">
			<p class="single-center-content">
				<input class="right" size="30" type="text" name="name" />Name of Entry:<br />
			</p>
			<p class="single-center-content-center">
				<input type="radio" name="choose" value="upload" checked="checked" onclick="javascript:tabswitcher(new Array('upload-radio','link-radio'))" /> Upload File (2MB max)
				<input type="radio" name="choose" value="link" onclick="javascript:tabswitcher(new Array('link-radio','upload-radio'))" /> Link to File
			</p>
			<div id="upload-radio" style="text-align: center; margin: 12px;">
				<input size="40" type="file" name="upload" />
			</div>
			<div id="link-radio" style="text-align: center; margin: 12px; display: none;">
				<input size="40" type="text" name="link" />
			</div>
			<p class="single-center-content-center">
				Comments:<br />
				<textarea rows="5" cols="40" name="comments"></textarea>
			</p>
			<p class="single-center-content-center">
				<input type="submit" value="Create!" />
			</p>
		</fieldset>
	</form>
</div>