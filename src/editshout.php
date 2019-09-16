<?php

	//login and other user stuffs
	include 'functions.php';
	include 'logins.php';
	require_once("db.inc.php");
	if (!(isset($lvl) && ($lvl >= 35))) fail("You aren't logged in, or you don't have permission to do this.","index.php");
	
if ( isset($_GET['id']) && $_GET['id'] != "" )
{?>

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
			Edit Shout lol?
			</div>
				<div id="shouts">
				<?php
					$result = mysql_query("SELECT shouts.uid,shout,time,avtype,users.uid AS uname FROM shouts LEFT JOIN users ON shouts.uid = users.userID WHERE shoutID='".mysql_real_escape_string($_GET['id'])."'");
					$valid = false;
					if(mysql_num_rows($result) == 1)
					{
						$row = mysql_fetch_array($result);
						$valid = true;
						$usrid=$row['uid'];
						$shoutz=shoutprocess($row['shout']);
						$rawshout=$row['shout'];
						$timez=date(H,$row['time']) . ":" . date(i,$row['time']);
						$token = strtok($shoutz, " ");
						
						$usar = $row['uname'];
						$avtype=$row['avtype'];
						
						$avatar=getavatar($usrid,$avtype,true);
						
						if ($token=="/me")
						{
							$shouts='<p class="shout">
									<span><a href="user.php?name=' . $usar . '"><img src="' . $avatar . '"/></a>
									<b><a href="user.php?name=' . $usar . '">' . trim($usar) . '</a> ' . trim(substr($shoutz,3)) . '</b> ' . $timez . '</span>
									</p>';
						}
						elseif ($token=="/zap")
						{
							$token=strtok(" ");
							$num=strlen($token)+5;
							$shouts='<p class="shout">
									<span><a href="user.php?name=' . $usar . '"><img src="' . $avatar . '" /></a>
									<b>' . $token . ' ' . trim(substr($shoutz,$num)) . '</b> ' . $timez . '</span>
									</p>';
						}
						else
						{
							$shouts='<p class="shout">
									<span><a href="user.php?name=' . $usar . '"><img src="' . $avatar . '" /></a><a href="user.php?name=' . $usar . '">' . $usar . '</a>
									' . $shoutz . " " . $timez . '</span>
									</p>';
						}
						
						echo $shouts;
					}
					else echo "Bad Shout ID";
				?>
					<!--<p class="shout">
					<span>
					<a href="user.php?name=lawler"><img src="avatars/1983_small.png" /></a><a href="user.php?name=lawler">Lawler</a>
					lawler! 02:13</span>
					</p>-->
				</div>
			<?php
				if ($valid)
				{
					echo '<form name="edit_shout" action="editshout.php?doit=' . $_GET['id'] . '" method="post">
					<textarea name="editshout" rows="5" cols="20">' . $rawshout . '</textarea><br />
					EDIT LOL? <input type="submit" value="Yes lol." style="margin-bottom: 5px;" /></form>';
				}
			?>
			
	</div>

				
</body></html>
<?php
}
elseif (isset($_GET['doit']) && $_GET['doit']!="" && isset($_POST['editshout']) && trim($_POST['editshout'])!="" && isset($_SESSION['lvl']) && ($_SESSION['lvl'] != "") && ($_SESSION['lvl'] >=35))
{
	$shoutval=htmlfilter($_POST['editshout']);
	$updateid = mysql_real_escape_string($_GET['doit']);
	$updateshout = mysql_real_escape_string($shoutval);
	mysql_query("UPDATE shouts SET shout='$updateshout' WHERE shoutID = '$updateid'");
	header("Location: shoutboxlive.php");
}
else fail("No action specified","index.php");
?>