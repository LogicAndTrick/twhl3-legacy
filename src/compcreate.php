<?
	$activeq = mysql_query("SELECT * FROM compos LEFT JOIN comptypes ON comptype = comptypeID WHERE compclose > '$thenow' AND compopen > 0 ORDER BY compclose DESC");
	if (!(isset($lvl) && ($lvl >= 40))) fail("You aren't logged in, or you don't have permission to do this.","competitions.php");
	if (mysql_num_rows($activeq) > 0) fail("There is already a competition active, wait for it to finish before creating a new tutorial.","competitions.php");
?>
<div class="single-center">
	<h1>Competition Creation</h1>
	<h2><a href="competitions.php">Competitions</a></h2>
	<p class="single-center-content"> 
		Welcome to the Competition Creation area. From here, you can create and active new competitions for TWHL. You can set start and end date, as well as all the applicable criteria for your respective competition.
	</p>	
</div>
<div class="single-center" id="gap-fix">
	<h1>Competition Create</h1>
	<form action="compadd.php" method="post" enctype="multipart/form-data">
		<fieldset class="new-thread">
			<p class="single-center-content">
				<input class="right" size="30" type="text" name="compname" />Name of Compo:<br />
				<select class="right" name="comptype">
					<option value="1">Full Map</option>
					<option value="2">Map From Base</option>
				</select>Type:<br />
				<select class="right" name="compgame">
					<option value="1">Goldsource</option>
					<option value="2">Source</option>
					<option value="3">Both (judged together)</option>
					<option value="4">Both (judged seperately)</option>
				</select>Engine:<br />
				<input class="right" size="30" type="text" name="compstart" />Start Date (DD MM YYYY):<br />
				<input class="right" size="30" type="text" name="compend" />End Date (DD MM YYYY):<br />
			</p>
			<p class="single-center-content">
				Outline:<br />
				<textarea rows="10" cols="76" name="compdesc"></textarea>
			</p>
			<p class="single-center-content">
				Additional Restrictions:<br />
				<textarea rows="5" cols="76" name="comprest"></textarea>
			</p>
			<p class="single-center-content">
				<input class="right" size="30" type="file" name="comppic" />Image (if applicable):<br />
				<input class="right" size="30" type="file" name="compfile" />Download (if applicable):<br />
			</p>
			<p class="single-center-content-center">
				<input type="submit" value="Create!" />
			</p>
		</fieldset>
	</form>
</div>