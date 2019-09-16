<div class="single-center">
	<h1>Submit a Map</h1>
	<p class="single-center-content">
		We have a couple of guidelines about map submissions on TWHL. Follow them and the community will greatly appreciate it.
	</p>
	<ul>
		<li>Currently, we have a 2MB limit on maps that you upload to our server. This is due to server constraints. If your map is larger than that, you'll need to find alternate hosting and then link <em>directly</em> to the file.</li>
		<li>Do not upload duplicate maps. If you have done it accidently, please remove the problem map(s).</li>
		<li>A screenshot is mandatory. If you don't add one, the map won't be submitted.</li>
		<li>The screenshot must be a JPEG (*.jpg). It will be resized automatically.</li>
		<li>'Allow rating' lets people rate your map. Turning it off doesn't reset the rating! </li>
		<li>'Allow uploads' lets people upload maps with their comments. Only turn this on if you are submitting a map you want people to fix up. </li>
		<li>Note: you can edit all this information later (and re-upload the file) if you need to!</li>
	</ul>
</div>	
<div class="single-center" id="gap-fix">
	<h1>Map Submission Form</h1>
	<div class="filter">
		<form action="vaultaddmap.php" method="post" enctype="multipart/form-data">
			<div class="filter-left">
				What game is your map for?<br />
				<select name="game">
<?
						$res2 = mysql_query("SELECT * FROM mapgames ORDER BY gameorder ASC");
						while($rowe = mysql_fetch_array($res2)) {
?>
						<option value="<?=$rowe['gameID']?>"><?=$rowe['gamename']?></option>
<?
						}
?>
				</select>
				<br />
				<br />
				What category is it?<br />
				<select name="category">
<?
						$res = mysql_query("SELECT * FROM mapcats ORDER BY catorder ASC");
						while($rowg = mysql_fetch_array($res)) {
?>
						<option value="<?=$rowg['catID']?>"><?=$rowg['catname']?></option>
<?
						}
?>
				</select>
			</div>
			<div class="filter-right">
				Choose your options:<br />
				<input type="checkbox" name="ratings" style="margin-left: 5px;" /> Allow ratings<br />
				<input type="checkbox" name="uploads" style="margin-left: 5px;" /> Allow uploads<br />
				<input type="checkbox" name="pmcomment" style="margin-left: 5px;" /> PM on comment<br />
			</div>
			<div class="filter-center">
				Map name:<br />
				<input name="name" type="text" size="20" />
				<div class="filter-include">
					Includes:<br />
					<input type="checkbox" name="RMF"/> RMF<br />
					<input type="checkbox" name="BSP"/> BSP<br />
					<input type="checkbox" name="MAP"/> MAP<br />
				</div>
			</div>
			<div class="filter-bottom" id="mapsubmit-bottom">
				<input type="radio" name="link" value="file" onclick="javascript:mapsubtoggle(1)" checked="checked" /> File Upload (maximum 2MB)
				<input type="radio" name="link" value="link" onclick="javascript:mapsubtoggle(0)" /> Link to file
				<div id="upload-div" style="display: block">
					<input type="file" size="80" name="upload"/>
				</div>
				<div id="link-div" style="display: none">
					<input type="text" size="80" name="uplink"/>
				</div><br />
				Upload your screenshot. You can add extra screenshots later.<br />
				<input type="file" size="80" name="image" /><br /><br />
				Give a description of your map.<br />
				<textarea cols="76" rows="10" name="details"></textarea><br />
				<input type="submit" value="Submit"/>
			</div>
		</form>
	</div>
</div>