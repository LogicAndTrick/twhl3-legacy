<?php

include 'functions.php';
include 'logins.php';
$redir="progress.php?edit=1";

require_once("db.inc.php");
		
if (isset($_SESSION['lvl']) and $_SESSION['lvl']>2 and isset($_POST['new']))
{
	if ($_POST['new']!="")
	{
	
		
		if (isset($_GET['newtitle']) and $_GET['newtitle']==1)
		{
			

			$text=$_POST['new'];
			
			$ida = mysql_query("SELECT * FROM progress ORDER BY titleid DESC LIMIT 1") or die("Unable to verify user because : " . mysql_error());
			$idl = mysql_fetch_array($ida);
			$id = $idl['titleid'];
			if (!isset($id))
			{
				$id=0;
			}
			$id+=1;
			
			$sql="INSERT INTO progress (titleid,itemid,noteid,text) VALUES ('$id','0','0','$text')";

			
			
			if (!mysql_query($sql,$dbh))
			{
				die('Error: ' . mysql_error());
			}
			  
			
		}
		elseif (isset($_GET['newitem']) and $_GET['newitem']==1)
		{
			

			$text=$_POST['new'];
			
			$topid=$_GET['title'];
			
			$ida = mysql_query("SELECT * FROM progress WHERE titleid='$topid' ORDER BY itemid DESC LIMIT 1") or die("Unable to verify user because : " . mysql_error());
			$idl = mysql_fetch_array($ida);
			$id = $idl['itemid'];
			if (!isset($id))
			{
				$id=0;
			}
			$id+=1;
			
			$sql="INSERT INTO progress (titleid,itemid,noteid,text) VALUES ('$topid','$id','0','$text')";

			
			
			if (!mysql_query($sql,$dbh))
			{
				die('Error: ' . mysql_error());
			}
			  
			
		}
		elseif (isset($_GET['newnote']) and $_GET['newnote']==1)
		{
			

			$text=$_POST['new'];
			
			$topid=$_GET['title'];
			$itid=$_GET['item'];
			
			$ida = mysql_query("SELECT * FROM progress WHERE titleid='$topid' AND itemid='$itid' ORDER BY noteid DESC LIMIT 1") or die("Unable to verify user because : " . mysql_error());
			$idl = mysql_fetch_array($ida);
			$id = $idl['noteid'];
			if (!isset($id))
			{
				$id=0;
			}
			$id+=1;
			
			$sql="INSERT INTO progress (titleid,itemid,noteid,text) VALUES ('$topid','$itid','$id','$text')";

			
			
			if (!mysql_query($sql,$dbh))
			{
				die('Error: ' . mysql_error());
			}
			  
			
		}		
	}
}
elseif (isset($_SESSION['lvl']) and $_SESSION['lvl']>2 and isset($_GET['delete']))
{
	
	$delete=$_GET['delete'];
	
	$sql="UPDATE progress SET text='noWRONGbad' WHERE entryID='$delete'";
	if (!mysql_query($sql,$dbh))
	{
		die('Error: ' . mysql_error());
	}
	//$redir='index.php';
	  
}
elseif (isset($_SESSION['lvl']) and $_SESSION['lvl']>2 and isset($_GET['done']))
{
	
	$done=$_GET['done'];
	$donea = mysql_query("SELECT * FROM progress WHERE entryID='$done'") or die("Unable to verify user because : " . mysql_error());
	$drow = mysql_fetch_array($donea);
	$dtext=$drow['text'];
	$newtext="<del>" . $dtext . "</del>";
	
	$sql="UPDATE progress SET text='$newtext' WHERE entryID='$done'";
	if (!mysql_query($sql,$dbh))
	{
		die('Error: ' . mysql_error());
	}
	//$redir='index.php';
	  
}

mysql_close($dbh);

header("Location: $redir");

?>