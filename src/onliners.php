<h1>Onliners</h1>
<div class="onliners">
<?php
	$cuserid=mysql_real_escape_string($_SESSION['usr']);
	$result = mysql_query("SELECT * FROM users WHERE userID != '$cuserid' ORDER BY lastclick DESC LIMIT 7");

	while($row = mysql_fetch_array($result))
	{
		$user_id = $row['userID'];
		$user_name = $row['uid'];
		$last_click = $row['lastclick'];
		$last_place = $row['lastplace'];
		$thenow = gmt(U);
		$seconds = $thenow - $last_click;
		$inwords = "";
		if ($seconds < 60) $inwords = ($seconds) . " secs";
		elseif ($seconds < 3600) $inwords = round($seconds/60) . " mins";
		elseif ($seconds < 86400) $inwords = round($seconds/(3600)) . " hours";
		else $inwords = round($seconds/(86400)) . " days";
		
		echo '<span>'.$inwords.'</span><p><a href="/user.php?id='.$user_id.'" title="'.$last_place.'">'.$user_name.'</a></p>'."\n";
		
	}
?>
</div>
