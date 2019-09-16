<?php

	//login and other user stuffs
	include 'middle.php';
	
?>
<?php echo'<?xml version="1.0" encoding="UTF-8" ?>'; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
      "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>TWHL3 Shoutbox LIVE: Spam Ahoy!</title>
		<link rel="stylesheet" type="text/css" href="shoutbox.css" />
		<link rel="shortcut icon" href="favicon.ico" />
		<script type="text/javascript" src="jshax.js"></script>
	</head>
	<body>
	<div id="container">
			<div id="header">
			Welcome to ShoutBOX: Live
			</div>
			<div id="shouts">
<?php

$result = mysql_query("SELECT * FROM
(SELECT shoutID,shout,time,avtype,lvl,users.uid AS uname,shouts.uid AS user_id FROM shouts LEFT JOIN users ON shouts.uid = users.userID ORDER BY shoutID DESC LIMIT 50) AS iq
ORDER BY shoutID ASC");

while($row = mysql_fetch_array($result))
{
	$usrid=$row['user_id'];
	$shoutz=shoutprocess($row['shout'],$row['lvl']);
	$timez=date(H,$row['time']) . ":" . date(i,$row['time']);
	$token = strtok($shoutz, " ");
	$usar = $row['uname'];
	$avtype = $row['avtype'];
	
	$edit=" ";
	if (isset($uid) && $lvl >= 35)
		$edit = ' <a href="editshout.php?id=' . $row['shoutID'] . '">[E]</a> <a href="deleteshout.php?id=' . $row['shoutID'] . '">[D]</a> ';
	
	$avatar = getavatar($usrid,$avtype,true);
	
	echo '<p class="shout">
	<span class="content"><a href="user.php?name=' . $usar . '"><img src="' . $avatar . '" /></a>';
		
	if ($token=="/me") echo '<b><a href="user.php?name=' . $usar . '">' . trim($usar) . '</a> ' . trim(substr($shoutz,3)) . '</b>'; 
	elseif ($token==$shoutbox_secret) echo '<b>' . trim(substr($shoutz,$shoutbox_secret_trim)) . '</b>';
	else echo '<a href="user.php?name=' . $usar . '">' . $usar . '</a> ' . $shoutz;
	
	echo $edit . $timez . '</span>
	</p>';
}

mysql_close($dbh);

echo "</div>";
echo '<a href="javascript:reloadshoutboxlive()">Refresh</a>';

if (isset($uid))
	echo '<form name="shout_form" action="shout.php" method="post">
		<input type="hidden" name="return" value="' . $_SERVER['PHP_SELF']."?".str_replace("&","&amp;",$_SERVER['QUERY_STRING']) . '" />
		<input type="text" size="16" name="shout" maxlength=100 onFocus=clear_textbox() value="Type here" style="margin: 0 0 5px 5px;" />
		<input type="submit" value="Go" style="margin-bottom: 5px;">
		</form>';
else
	echo '<br><input type="text" size="14" disabled="disabled" value="Login to Shout" style="margin: 0 0 5px 5px;" />
		<input type="button" value="Go" style="margin-bottom: 5px;">';

?>
</div>
</body>
</html>