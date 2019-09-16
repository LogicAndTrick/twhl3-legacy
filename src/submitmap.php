<?php
	//login and other user stuffs
	include 'functions.php';
	include 'logins.php';
	//content
	include 'header.php';
	include 'sidebar.php';
	

	if (isset($_SESSION['uid']))
		echo '
		<div id="about">
		
			<form name="mapvaultsub" action="addmap.php" method="post">

			<b>Map Details</b><br><br>

			Map Name: <input type="text" name="name"><br>
			
			Category:
			<select name="cat">
			<option value="1">Completed Maps
			<option value="2">Example Maps
			<option value="3">Problem Maps
			<option value="4">Unfinished Stuff
			</select><br>

			Mod:
			<select name="mod">
			<option value="1">HL</option>
			<option value="2">HLDM</option>
			<option value="3">HL2</option>
			<option value="4">HL2DM</option>
			<option value="5">CS</option>
			<option value="6">CS:S</option>
			<option value="7">TFC</option>
			<option value="8">DoD</option>
			<option value="9">Spirit</option>
			<option value="10">Other</option>
			</select><br>

			ZIP or RAR file: <input type="file" name="download"> (2MB max!)<br>
			<b>or</b> Link: <input type="text" name="downloadlink" value="http://"><br>
			Screenshot: <input type="file" name="screenie"><br>
			
			Included:
			<input type="checkbox" name="inrmf" value="1">&nbsp;rmf/vmf&nbsp;&nbsp;
			<input type="checkbox" name="inmap" value="1">&nbsp;map&nbsp;&nbsp;
			<input type="checkbox" name="inbsp" value="1">&nbsp;bsp<br>

			Allow Rating: <input type="radio" name="rating" value="1" checked>&nbsp;yes&nbsp;&nbsp;
			<input type="radio" name="rating" value="0">&nbsp;no<br>
			
			Allow uploads: <input type="radio" name="uploads" value="1">&nbsp;yes&nbsp;&nbsp;
			<input type="radio" name="uploads" value="0" checked>&nbsp;no<br>
			
			<input type="checkbox" name="pmoncomment" value="1" checked="checked"> Private message when a comment posted<br><br>


			Description:<br>
			<textarea name="info" cols="50" rows="6"></textarea><br>

			<input type="submit" name="sub" value="Submit!">
			</form>
				
		</div>';
	else
		echo 'you need to be logged in!';
	
	
	include 'footer.php';
?>