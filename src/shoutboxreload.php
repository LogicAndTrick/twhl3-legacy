<?
	include 'functions.php';
	include 'logins.php';
	
	$shoutq = mysql_query("SELECT shoutID,sq.uid,lvl,shout,time,users.uid AS uname FROM (SELECT * FROM shouts ORDER BY shoutID DESC LIMIT 7) as sq LEFT JOIN users ON sq.uid = users.userID ORDER BY shoutID ASC");
	
		while ($shtr = mysql_fetch_array($shoutq))
		{
			$username=$shtr['uname'];
			$shoutid=$shtr['shoutID'];
			$userid=$shtr['uid'];
			$shouttext=shoutprocess($shtr['shout'],$shtr['lvl']);
			$shouttime=timezone($shtr['time'],$_SESSION['tmz'],"H:i");
			$token = strtok($shouttext, " ");
			
			$tokenstring = "";
				
			if ($token=="/me") $tokenstring =  '<b class="purple">'.trim($username).' '.trim(substr($shouttext,3)) . '</b>'; 
			elseif ($token==$shoutbox_secret) $tokenstring =  '<b class="purple">' . trim(substr($shouttext,$shoutbox_secret_trim)) . '</b>';
			else $tokenstring =  $shouttext;
			
			if (isset($lvl) && ($lvl >= 35))
			{
?>
		<span class="time"><a href="javascript:show_id('shout_<?=$shoutid?>')" onmouseout="javascript:hide_id_delay('shout_<?=$shoutid?>')"><?=$shouttime?></a></span>
		<div id="shout_<?=$shoutid?>" class="admin-drop" onmouseover="javascript:stop_hide_delay('shout_<?=$shoutid?>')" onmouseout="javascript:hide_id_delay('shout_<?=$shoutid?>')" style="display: none;">
			<ul>
				<li><a href="javascript:ajax_shoutbox_mod('edit=<?=$shoutid?>')">[E]</a></li>
				<li><a href="javascript:ajax_shoutbox_mod('delete=<?=$shoutid?>')">[D]</a></li>
			</ul>
		</div>
<?
			}
			else
			{
?>
		<span class="time"><?=$shouttime?></span>
<?
			}
?>
		<h2><a href="user.php?id=<?=$userid?>"><?=$username?></a></h2>
		<p>
			<?=$tokenstring?>
		</p>
<?
		}
?>