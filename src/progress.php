<?php

die();
	//login and other user stuffs
	include 'functions.php';
	include 'logins.php';
	//content
	include 'header.php';
	include 'sidebar.php';
	

	echo '<div class="single-center">
			<div class="single-content">
			<h1>To Do/Brainstorming</h1>
			<span class="progress"><a href="progress.php">View</a>|<a href="progress.php?edit=1">Edit</a></span>
			<dl>
			';
			
	$start=1;
	$stop=0;
	
	if (isset($_SESSION['lvl']) and $_SESSION['lvl']>2 and $_GET['edit']==1)
		$yes=1;
	else
		$yes=0;
	
	$linky="";
	while ($stop==0)
	{
		$go = mysql_query("SELECT * FROM progress WHERE titleid='$start' AND itemid='0' AND noteid='0'") or die("Unable to verify user because : " . mysql_error());
		$goa = mysql_fetch_array($go);
		if (isset($goa['text']) and $goa['text']!="")
		{
			if ($goa['text']!="noWRONGbad")
			{
				if ($yes==1) $linky=' <a href="addprogress.php?done=' . $goa['entryID'] . '">Done</a> <a href="addprogress.php?delete=' . $goa['entryID'] . '">Delete</a>';
				echo '<dt>
				' . $goa['text'] . $linky . '
				';
				$start1=1;
				$stop1=0;
				
				while ($stop1==0)
				{
					$go1 = mysql_query("SELECT * FROM progress WHERE titleid='$start' AND itemid='$start1' AND noteid='0'") or die("Unable to verify user because : " . mysql_error());
					$goa1 = mysql_fetch_array($go1);
					if (isset($goa1['text']) and $goa1['text']!="")
					{
						if ($goa1['text']!="noWRONGbad")
						{
							if ($yes==1) $linky=' <a href="addprogress.php?done=' . $goa1['entryID'] . '">Done</a> <a href="addprogress.php?delete=' . $goa1['entryID'] . '">Delete</a>';
							echo '<dd>
							' . $goa1['text'] . $linky . '
							';
							$start2=1;
							$stop2=0;
							
							while ($stop2==0)
							{
								$go2 = mysql_query("SELECT * FROM progress WHERE titleid='$start' AND itemid='$start1' AND noteid='$start2'") or die("Unable to verify user because : " . mysql_error());
								$goa2 = mysql_fetch_array($go2);
								if (isset($goa2['text']) and $goa2['text']!="")
								{
									if ($goa2['text']!="noWRONGbad")
									{
										if ($yes==1) $linky=' <a href="addprogress.php?done=' . $goa2['entryID'] . '">Done</a> <a href="addprogress.php?delete=' . $goa2['entryID'] . '">Delete</a>';
										echo '<p>
										' . $goa2['text'] . $linky . '
										</p>
										';
									}
								}
								else
									$stop2=1;
									
							$start2+=1;
							}
							
							if ($yes==1)
							echo '<form  action="addprogress.php?newnote=1&title=' . $start . '&item=' . $start1 . '" method="post"><input name="new" maxlength=100><input type="submit" value="Add Note"></form>
							</dd>
							';
							else
							echo '</dd>
									';
						}
					}
					else
						$stop1=1;
						
				$start1+=1;
				}
				
				if ($yes==1)
				echo '<form id=second action="addprogress.php?newitem=1&title=' . $start . '" method="post"><input name="new" maxlength=100><input type="submit" value="Add Item"></form>
				</dt>
				';
				else
				echo '</dt>
						';
			}
			
		}
		else
			$stop=1;
			
		$start+=1;
	}
	
	if ($yes==1)
	echo '<br>
			<form action="addprogress.php?newtitle=1" method="post"><input name="new" maxlength=100><input type="submit" value="Add Title"></form>
			</dl></div></div>';
	else
		echo '</dl></div></div>';
	
	include 'footer.php';
?>