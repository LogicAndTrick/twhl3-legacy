<?
	if (isset($_POST['pmprev']))
	{
		include 'top.php';
		$userqu = mysql_query("SELECT * FROM users WHERE userID = '$usr'");
		
		if (!(isset($usr) && ($usr != ""))) fail("You must be logged in to do this.","index.php");
		if (mysql_num_rows($userqu) == 0) fail("You were not found.","index.php");

			$userrow = mysql_fetch_array($userqu);
			$thenow = gmt("U");
			$avatar = getresizedavatar($userrow['userID'],$userrow['avtype'],100);
			$date = timezone($thenow,$_SESSION['tmz'],"jS F Y");
			$smalldate = timezone($thenow,$_SESSION['tmz'],"H:i");
		
	$inq = mysql_query("SELECT * FROM pminbox LEFT JOIN users ON pmfrom = userID WHERE pmto = '$usr' AND isnew >= 0 ORDER BY pmtime DESC");
	
	$num_inbox = mysql_num_rows($inq);
	
	if ($num_inbox > 200 && ($lvl < 20 || !isset($lvl))) fail("You cannot send a message until your inbox has 200 or less messages in it","privmsg.php");
?>
<div class="single-center">
	<h1>Preview Private Message</h1>
	<h2><a href="privmsg.php">Back to Inbox</a> | <a href="privmsg.php?send">Compose New Message</a></h2>
	<div class="private-message-container">
		<span class="right-avatar">
			<img src="<?=$avatar?>" alt="avatar" />
		</span>
		<p class="right-info">
			Subject: <?=stripslashes($_POST['pmsub'])?><br />
			From <a href="user.php?id=<?=$userrow['userID']?>"><?=$userrow['uid']?></a><br />
			To: <?=$_POST['pmto']?><br />
			<strong><?=$date?></strong><br />
			<?=$smalldate?>
		</p>
		<div class="message">
			<?=post_format(stripslashes($_POST['pmtext']))?>
		</div>
	</div>
</div>
<div class="single-center" id="gap-fix-bottom">
	<h1>Modify Message</h1>
	<form method="post" action="privsend.php">
		<fieldset class="new-thread">
			<p class="single-center-content">
				<input class="right" size="40" type="text" name="pmto" value="<?=$_POST['pmto']?>" />To:<br />
				<input class="right" size="40" type="text" name="pmsub" value="<?=$_POST['pmsub']?>" />Subject:
			</p>
			<p class="single-center-content">
				<textarea rows="10" cols="76" name="pmtext"><?=$_POST['pmtext']?></textarea>
			</p>
			<p class="single-center-content-center">
				<input type="submit" name="pmprev" value="Preview" />
				<input type="submit" name="pmpost" value="Post" />
			</p>
		</fieldset>
	</form>
</div>
<?
		include 'bottom.php';
	}
	elseif (isset($_POST['pmpost']))
	{
		include 'middle.php';
		if (!(isset($usr) && ($usr != ""))) fail("You must be logged in to do this.","index.php");
		$pmto = mysql_real_escape_string(trim($_POST['pmto']));
		$pmtoq = mysql_query("SELECT * FROM users WHERE uid = '$pmto'");
		$pmsub = mysql_real_escape_string(trim($_POST['pmsub']));
		$pmtext = htmlfilter($_POST['pmtext']);
		$thenow = gmt("U");
		
		
		
		$inq = mysql_query("SELECT * FROM pminbox LEFT JOIN users ON pmfrom = userID WHERE pmto = '$usr' AND isnew >= 0 ORDER BY pmtime DESC");
		$num_inbox = mysql_num_rows($inq);
		if ($num_inbox > 200 && ($lvl < 20 || !isset($lvl))) fail("You cannot send a message until your inbox has 200 or less messages in it","privmsg.php",true);
		
		if ((mysql_num_rows($pmtoq) > 0) && $pmsub!="" && $pmto!="" && $pmtext!="" && isset($usr) && $usr!="")
		{
			$pmtor = mysql_fetch_array($pmtoq);
			$usertosend = $pmtor['userID'];
			mysql_query("INSERT INTO pminbox (pmto,pmfrom,pmtime,pmsubject,pmtext,isnew) VALUES ('$usertosend','$usr','$thenow','$pmsub','$pmtext','1')");
			header("Location: privmsg.php");
		}
		else fail("User not found, or message not valid. (Don't leave anything blank!)","privmsg.php",true);
	}
	else 
	{
		include 'top.php';
		fail("No action specified.","privmsg.php");
	}
?>