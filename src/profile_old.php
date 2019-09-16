<?php




$yes=0;

		
	if (isset($_GET['name']))
	{
		$usrnm=mysql_real_escape_string($_GET['name']);
		$result = mysql_query("SELECT * FROM users WHERE uid='$usrnm'");
	}
	elseif (isset($_GET['id']))
	{
		$usrid=mysql_real_escape_string($_GET['id']);
		$result = mysql_query("SELECT * FROM users WHERE userID='$usrid'");
	}
	
		$row = mysql_fetch_array($result);
		$user=$row['uid'];
		$access=axslvl($row['lvl']);
		$logins=$row['log'];
		$usrid=mysql_real_escape_string($row['userID']);
		$datej=$row['joined'];
		$dated=date(j,$datej) . date(S,$datej) . " " . date(F,$datej). " " . date("Y",$datej);
		$yes=1;		
		
		$avtype=$row['avtype'] ;
		$avatar=getavatar($usrid,$avtype);
		
	$email=$row['email'];
	$allow=$row['allowemail'];
	$gend=$row['gender'];
	$lastlog=$row['lastlogin'];
	//$row['lastplace'] . " " . $row['lastclick'] . " " . 
	$bio=$row['bio'];
	//$row['timezone'] . " " . $row['stat_profilehits'] . " " . 
	$posts=$row['stat_posts'];
	$shouts=$row['stat_shouts'];
	$maps=$row['stat_maps'];
	$mvcoms=$row['stat_mvcoms'];
	$journs=$row['stat_journals'];
	$jcoms=$row['stat_jcoms'];
	$tuts=$row['stat_tuts'];
	$tcoms=$row['stat_tutcoms'];
	$projs=$row['stat_projects'];
	$pcoms=$row['stat_pcoms'];
	$ptracks=$row['stat_ptracking'];
	$rname=$row['info_realname'];
	$website=$row['info_website'];
	$job=$row['info_job'];
	$interests=$row['info_interests'];
	$langs=$row['info_lang'];
	$aim=$row['info_aim'];
	$msn=$row['info_msn'];
	$xfire=$row['info_xfire'];
	$cpu=$row['pc_cpu'];
	$ram=$row['pc_ram'];
	$hdd=$row['pc_hdd'];
	$gpu=$row['pc_gpu'];
	$mon=$row['pc_mon'];
	$os=$row['pc_os'];
	$bday=$row['birthday'];
  
  

	$lastl=gmt(U)-$lastlog;
	$lastd=$lastl/86400;
	if ($lastd <= 1)
		$lastlogday="Today";
	else if ($lastd < 2)
		$lastlogday="1 Day Ago";
	else
		$lastlogday=ceil($lastd) . " Days Ago";
		
	if ($website=="")
		$website = "No website";
	elseif (substr($website,0,7)=="http://")
		$website = '<a href="' . $website . '">' . $website . '</a>';
	else
		$website = '<a href="http://' . $website . '">http://' . $website . '</a>';
		
	if ($job=="")
		$job = "-";
		
	if ($rname=="")
		$rname = "-";
		
	if ($website=="")
		$website = "-";
		
	if ($interests=="")
		$interests = "-";
		
	if ($bio=="")
		$bio = "-";
		
	if ($allow!=1)
		$email = "-";
		
	if ($msn=="")
		$msn = "-";
	
/*
	
	$posts=0;
	$result = mysql_query("SELECT * FROM posts WHERE poster='$user'");
		while($row = mysql_fetch_array($result))
		{
			$posts+=1;
		}
		
	$shouts=0;
	$result = mysql_query("SELECT * FROM shouts WHERE uid='$user'");
		while($row = mysql_fetch_array($result))
		{
			$shouts+=1;
		}
		
	$journals=0;
	$result = mysql_query("SELECT * FROM journals WHERE ownerID='$usrid'");
		while($row = mysql_fetch_array($result))
		{
			$journals+=1;
		}
*/
	$dayz=gmt(U)-$datej;
	$days=ceil($dayz/86400);
	
	if ($_SESSION['lvl']>4)
		$admin='
				<div class="stat-box" id="ban">
						<h3>Ban user? Delete:</h3>
						<dl>
						<form name="banuser" action="banuser.php?ban=' . $usrid . '" method="post">
						<input type="hidden" name="userid" value="' . $usrid . '">
							<dt>Shouts</dt><dd><input type="checkbox" name="shouts" value="1"></dd>

							<dt class="alt">Forum posts</dt><dd class="alt"><input type="checkbox" name="posts" value="1"></dd>
							<dt>Maps</dt><dd><input type="checkbox" name="maps" value="1"></dd>
							<dt class="alt">MapVault Comments</dt><dd class="alt"><input type="checkbox" name="mcomments" value="1"></dd>
							<dt>Journals</dt><dd><input type="checkbox" name="journals" value="1"></dd>
							<dt class="alt">Journal Comments</dt><dd class="alt"><input type="checkbox" name="jcomments" value="1"></dd>
							<dt>Tutorials</dt><dd><input type="checkbox" name="tutorials" value="1"></dd>
							<dt class="alt">Tutorial Comments</dt><dd class="alt"><input type="checkbox" name="tcomments" value="1"></dd>
							<dt>Projects</dt><dd><input type="checkbox" name="projects" value="1"></dd>
							<dt class="alt">Project Comments</dt><dd class="alt"><input type="checkbox" name="pcomments" value="1"></dd>
							<dt>Duration</dt><dd><select name="time">
																			<option value="1">3 Days
																			<option value="2">1 Week
																			<option value="3">2 Weeks
																			<option value="4">3 Weeks
																			<option value="5">1 Month
																			<option value="6">3 Months
																			<option value="7">6 Months
																			<option value="8">1 Year
																			<option value="9">3 Years
																			<option value="10">Forever
																			</select></dd>
							<dt class="alt">Reason</dt><dd class="alt"><input type="text" name="why" size=8></dd>
							<dt>IP Ban</dt><dd><input type="checkbox" name="ipban" value="1"></dd>
							<dt class="alt"></dt><dd class="alt"><input type="submit" value="BANT"></dd>
						</form>
						</dl>
					</div>
					<div class="stat-box" id="oblit">
						<h3>OBLITERATE</h3>
						<dl>
						<form name="obliterateuser" action="banuser.php?obliterate=' . $usrid . '" method="post">
							<dt><input type="hidden" name="ouserid" value="' . $usrid . '"></dt><dd><input type="submit" value="DIE"></dd>
						</form>
						</dl>
					</div>';
	else
		$admin="";
	
	echo
		'<div id="user-profile">
				<h2><a href="user.php?list=1">Members</a>: ' . $user . '</h2>

				<div id="avatar">
					<img src="' . $avatar . '" alt="' . $user . '" />
					<span id="title">' . $access . '</span>
				</div>';
				
				//'USER: ' . $user . ' - ' . $access . '<br />
				//LOGINS: ' . $logins . '<br /><br />';
				
			echo '<div id="info">
					<h3>Info</h3>
					<dl>
						<dt>Joined:</dt><dd>' . $dated . ' (' . $days . ' days ago)</dd>

						<dt class="alt">Last login:</dt><dd  class="alt">' . $lastlogday . '</dd>
						<dt>Real Name:</dt><dd>' . $rname . '</dd>
						<dt class="alt">Website:</dt><dd class="alt">' . $website . '</dd>
						<dt>Occupation:</dt><dd>' . $job . '</dd>
						<dt class="alt">Interests:</dt><dd class="alt">' . $interests . '</dd>

					</dl>
				</div>
				<div id="box-wrap">
					<div class="stat-box" id="contact">
						<h3>Contact</h3>
						<dl>
							<dt>PM:</dt><dd><a href="#">Send PM</a></dd>

							<dt class="alt">E-mail:</dt><dd class="alt"><a href="mailto:' . $email . '">' . $email . '</a></dd>
							<dt>MSN:</dt><dd><a href="mailto:' . $msn . '">' . substr($msn,0,15) . '...</a></dd>
						</dl>
					</div>
					<div class="stat-box" id="stats">
						<h3>Stats</h3>

						<dl>
							<dt>Logins (per day):</dt><dd>' . $logins . ' (' . round($logins/$days,1) . ')</dd>
							<dt class="alt">Forum posts:</dt><dd class="alt">' . $posts . ' (' . round($posts/$days,1) . ')</dd>
							<dt>Shouts:</dt><dd>' . $shouts . ' (' . round($shouts/$days,1) . ')</dd>
							<dt class="alt">Maps:</dt><dd class="alt">' . $maps . ' (' . round($maps/$days,1) . ')</dd>
							<dt>MV comments:</dt><dd>' . $mvcoms . ' (' . round($mvcoms/$days,1) . ')</dd>
							<dt class="alt">Journal entries:</dt><dd class="alt">' . $journs . ' (' . round($journs/$days,1) . ')</dd>
							<dt>Journal comments:</dt><dd>' . $jcoms . ' (' . round($jcoms/$days,1) . ')</dd>
						</dl>
					</div>' . $admin . '
				</div>
				<div id="biography">
					<h3>Biography</h3>

					<p>' . post_format($bio) . '</p>
				</div>';
				
				echo '<div id="journal">
					<h3>Journal</h3>';
					
		$result = mysql_query("SELECT * FROM journals WHERE ownerID='$usrid' ORDER BY journalID DESC LIMIT 5");
		while($row = mysql_fetch_array($result))
		{
			$jdate1=$row['journaldate'];
			$jdate=date(d,$jdate1) . " " . date(M,$jdate1) . " " . date(y,$jdate1) . " " . date(H,$jdate1) . ":" . date(i,$jdate1);
			$jtext=$row['journaltext'];
			echo '<p class="entry">
						<span class="date">' . $jdate . '</span>
						' . $jtext . '
					</p>
					';
		}
		
		echo '<span id="old-entries">[<a href="#">Old entries (5)</a>]</span>';
		




echo '
				</div>
				<div id="maps">
					<h3>Maps</h3>
					<ul>
						<li><a href="#"><img src="testscreenshot.jpg"></a><a href="#">de_yay_boobs</a></li>
						<li><a href="#"><img src="testscreenshot.jpg"></a><a href="#">de_yay_boobs</a></li>
					</ul>

					<span id="other-maps">[<a href="#">See the other 42</a>]</span>
				</div>
			</div>';
			
			
?>