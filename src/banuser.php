<?php

	//login and other user stuffs
	include 'functions.php';
	include 'logins.php';
	//content
	include 'header.php';
	include 'sidebar.php';

	if (isset($_SESSION['uid']) and $_SESSION['lvl']>4)
	{
		if (isset($_GET['ban']) and $_GET['ban']!="" and $_GET['ban']==$_POST['userid'])
		{
			$userid=$_POST['userid'];
			echo "Banning user number $userid <br />";
			$shouts=$_POST['shouts'];
			$posts=$_POST['posts'];
			$maps=$_POST['maps'];
			$mcomments=$_POST['mcomments'];
			$journals=$_POST['journals'];
			$jcomments=$_POST['jcomments'];
			$tutorials=$_POST['tutorials'];
			$tcomments=$_POST['tcomments'];
			$projects=$_POST['projects'];
			$pcomments=$_POST['pcomments'];
			$time=$_POST['time'];
			$why=$_POST['why'];
			$ipban=$_POST['ipban'];
			//echo "shouts  $shouts posts $posts maps $maps mc $mcomments journals $journals
			//jc $jcomments tuts $tutorials tc $tcomments projects $projects pc $pcomments user $user duration $time reason $why";
			
			if ($shouts==1)
			{
				echo "shouts will be reset<br>";
			}
			if ($posts==1)
			{
				echo "posts will be reset<br>";
			}
			if ($maps==1)
			{
				echo "maps will be reset<br>";
			}
			if ($mcomments==1)
			{
				echo "mcomments will be reset<br>";
			}
			if ($journals==1)
			{
				echo "journals will be reset<br>";
			}
			if ($jcomments==1)
			{
				echo "jcomments will be reset<br>";
			}
			if ($tutorials==1)
			{
				echo "tutorials will be reset<br>";
			}
			if ($tcomments==1)
			{
				echo "tcomments will be reset<br>";
			}
			if ($projects==1)
			{
				echo "projects will be reset<br>";
			}
			if ($pcomments==1)
			{
				echo "pcomments will be reset<br>";
			}
			if ($ipban==1)
			{
				echo "IP will be banned<br>";
			}
			$dur="";
			if ($time==1) $dur="3 Days";
			elseif ($time==2) $dur="1 Week";
			elseif ($time==3) $dur="2 Weeks";
			elseif ($time==4) $dur="3 Weeks";
			elseif ($time==5) $dur="1 Month";
			elseif ($time==6) $dur="3 Months";
			elseif ($time==7) $dur="6 Months";
			elseif ($time==8) $dur="1 Year";
			elseif ($time==9) $dur="3 Years";
			elseif ($time==10) $dur="Eternity";
			echo "duration $dur<br>";
			echo "reason $why<br><br>";
			echo 'Are you really, really, really sure?<br>
			
						<form name="reallybanuser" action="banuser.php?definatelyban=' . $userid . '" method="post">
							<input type="hidden" name="userid" value="' . $userid . '">
							<input type="hidden" name="shouts" value="' . $shouts . '">
							<input type="hidden" name="posts" value="' . $posts . '">
							<input type="hidden" name="maps" value="' . $maps . '">
							<input type="hidden" name="mcomments" value="' . $mcomments . '">
							<input type="hidden" name="journals" value="' . $journals . '">
							<input type="hidden" name="jcomments" value="' . $jcomments . '">
							<input type="hidden" name="tutorials" value="' . $tutorials . '">
							<input type="hidden" name="tcomments" value="' . $tcomments . '">
							<input type="hidden" name="projects" value="' . $projects . '">
							<input type="hidden" name="pcomments" value="' . $pcomments . '">
							<input type="hidden" name="time" value="' . $time . '">
							<input type="hidden" name="why" value="' . $why . '">
							<input type="hidden" name="ipban" value="' . $ipban . '">
							<input type="submit" value="Banstick">
						</form>';
		}
		elseif (isset($_GET['definatelyban']) and $_GET['definatelyban']!="" and $_GET['definatelyban']==$_POST['userid'])
		{
		
			$userid=mysql_real_escape_string($_POST['userid']);
			echo "Banning user number $userid ...<br />";
			$shouts=$_POST['shouts'];
			$posts=$_POST['posts'];
			$maps=$_POST['maps'];
			$mcomments=$_POST['mcomments'];
			$journals=$_POST['journals'];
			$jcomments=$_POST['jcomments'];
			$tutorials=$_POST['tutorials'];
			$tcomments=$_POST['tcomments'];
			$projects=$_POST['projects'];
			$pcomments=$_POST['pcomments'];
			$time=$_POST['time'];
			$why=mysql_real_escape_string($_POST['why']);
			$ipban=$_POST['ipban'];
			//echo "shouts  $shouts posts $posts maps $maps mc $mcomments journals $journals
			//jc $jcomments tuts $tutorials tc $tcomments projects $projects pc $pcomments user $user duration $time reason $why";
			
			$usernm = mysql_query("SELECT * FROM users WHERE userID = '$userid'") or die("Unable to verify user because : " . mysql_error());
			$nmarray = mysql_fetch_array($usernm);
			$banuid = mysql_real_escape_string($nmarray['uid']);
			
			if ($shouts==1)
			{
				echo "deleting shouts...<br>";
				mysql_query("DELETE FROM shouts WHERE uid='$banuid'");
			}
			if ($posts==1)
			{
				echo "deleting posts and threads...<br>";
				mysql_query("DELETE FROM posts WHERE poster='$banuid'");
				mysql_query("DELETE FROM threads WHERE owner='$banuid'");
			}
			if ($maps==1)
			{
				echo "deleting maps...<br>";
				mysql_query("DELETE FROM maps WHERE ownerID='$userid'");
			}
			if ($mcomments==1)
			{
				echo "deleting mapvault comments...<br>";
				//mysql_query("DELETE FROM shouts WHERE uid='$banuid'");
			}
			if ($journals==1)
			{
				echo "deleting journals...<br>";
				mysql_query("DELETE FROM journals WHERE ownerID='$userid'");
			}
			if ($jcomments==1)
			{
				echo "deleting journal comments...<br>";
				//mysql_query("DELETE FROM shouts WHERE uid='$banuid'");
			}
			if ($tutorials==1)
			{
				echo "deleting tutorials...<br>";
				//mysql_query("DELETE FROM shouts WHERE uid='$banuid'");
			}
			if ($tcomments==1)
			{
				echo "deleting tutorial comments...<br>";
				//mysql_query("DELETE FROM shouts WHERE uid='$banuid'");
			}
			if ($projects==1)
			{
				echo "deleting projects...<br>";
				//mysql_query("DELETE FROM shouts WHERE uid='$banuid'");
			}
			if ($pcomments==1)
			{
				echo "deleting project comments...<br>";
				//mysql_query("DELETE FROM shouts WHERE uid='$banuid'");
			}
			/*if ($user==1)
			{
				echo "deleting user...<br>";
				mysql_query("DELETE FROM users WHERE userID='$userid'");
			}*/
			$dur="";
			$secs=0;
			if ($time==1) 
			{
				$dur="3 Days";
				$secs=3*24*60*60;
			}
			elseif ($time==2) 
			{
				$dur="1 Week";
				$secs=7*24*60*60;
			}
			elseif ($time==3) 
			{
				$dur="2 Weeks";
				$secs=2*7*24*60*60;
			}
			elseif ($time==4) 
			{
				$dur="3 Weeks";
				$secs=3*7*24*60*60;
			}
			elseif ($time==5) 
			{
				$dur="1 Month";
				$secs=4*7*24*60*60;
			}
			elseif ($time==6) 
			{
				$dur="3 Months";
				$secs=12*7*24*60*60;
			}
			elseif ($time==7) 
			{
				$dur="6 Months";
				$secs=26*7*24*60*60;
			}
			elseif ($time==8) 
			{
				$dur="1 Year";
				$secs=52*7*24*60*60;
			}
			elseif ($time==9) 
			{
				$dur="3 Years";
				$secs=3*52*7*24*60*60;
			}
			elseif ($time==10)
			{
				$dur="Eternity";
				$secs=1;
			}
			$btime=gmt(U)+$secs;
			$banip="0.0.0.0";
			if ($ipban==1)
			{
				echo "banning IP...<br>";
				$banip = mysql_real_escape_string($nmarray['ipadd']);
			}
			echo "inserting ban into database...<br>";
			if ($secs==1)
			{
				$secs="infinite";
				mysql_query("INSERT INTO bans (userID,IP,time,reason) VALUES ('$userid','$banip','1','$why')");
			}
			else mysql_query("INSERT INTO bans (userID,IP,time,reason) VALUES ('$userid','$banip','$btime','$why')");
			
			echo "User $banuid is now banned for $dur ($secs seconds)<br>";
			echo "Reason: $why";
		}
		elseif (isset($_GET['obliterate']) and $_GET['obliterate']!="" and $_GET['obliterate']==$_POST['ouserid'])
		{
			$ouserid=$_POST['ouserid'];
			echo "Obliterating user number $ouserid <br />";
			echo "are you REALLY REALLY REALLY sure?<br />";
			echo '<form name="reallyobliterateuser" action="banuser.php?definatelyobliterate=' . $ouserid . '" method="post">
						<input type="submit" value="Ultimate Banstick!"></dd><input type="hidden" name="ouserid" value="' . $ouserid . '">
					</form>';
		}
		elseif (isset($_GET['definatelyobliterate']) and $_GET['definatelyobliterate']!="" and $_GET['definatelyobliterate']==$_POST['ouserid'])
		{
			$ouserid=$_POST['ouserid'];
			$userid=$ouserid;
			echo "Obliterating user number $ouserid ...<br />";
			
			
			$usernm = mysql_query("SELECT * FROM users WHERE userID = '$userid'") or die("Unable to verify user because : " . mysql_error());
			$nmarray = mysql_fetch_array($usernm);
			$banuid = $nmarray['uid'];
			$banip = $nmarray['ipadd'];

			echo "deleting shouts...<br>";
			mysql_query("DELETE FROM shouts WHERE uid='$banuid'");
			echo "deleting posts and threads...<br>";
			mysql_query("DELETE FROM posts WHERE poster='$banuid'");
			mysql_query("DELETE FROM threads WHERE owner='$banuid'");
			echo "deleting maps...<br>";
			mysql_query("DELETE FROM maps WHERE ownerID='$userid'");
			echo "deleting mapvault comments...<br>";
			//mysql_query("DELETE FROM shouts WHERE uid='$banuid'");
			echo "deleting journals...<br>";
			mysql_query("DELETE FROM journals WHERE ownerID='$userid'");
			echo "deleting journal comments...<br>";
			//mysql_query("DELETE FROM shouts WHERE uid='$banuid'");
			echo "deleting tutorials...<br>";
			//mysql_query("DELETE FROM shouts WHERE uid='$banuid'");
			echo "deleting tutorial comments...<br>";
			//mysql_query("DELETE FROM shouts WHERE uid='$banuid'");
			echo "deleting projects...<br>";
			//mysql_query("DELETE FROM shouts WHERE uid='$banuid'");
			echo "deleting project comments...<br>";
			//mysql_query("DELETE FROM shouts WHERE uid='$banuid'");
			echo "making account transparent...<br>";
			mysql_query("UPDATE users SET lvl='-1' WHERE userID='$userid'");
			$secs="infinite";
			echo "inserting ban into database...<br>";
			mysql_query("INSERT INTO bans (userID,IP,time,reason) VALUES ('$userid','$banip','1','')");
			$dur='all eternity';
			echo "User $banuid is now banned for $dur ($secs seconds)<br>";
		}
		else echo "uh oh";
	}
	else echo "failure";
	
	include 'footer.php';
	
?>