<?php

require_once("db.inc.php");
include 'browserstats.php';
$timer = microtime() + time();

if ($_GET['leet']=="^_^" or $_GET['leet']=="%5E_%5E")
			{
				header("Location: /^_^.htm");
			}

?>
<?php echo'<?xml version="1.0" encoding="iso-8859-1" ?>'; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
      "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>TWHL: Half-Life/2 Mapping Tutorials and Resources</title>
		<link rel="stylesheet" type="text/css" href="/style_main<?=($is_ie6)?'_ie6':''?>.css" />
		<link rel="stylesheet" type="text/css" href="/style_single<?=($is_ie6)?'_ie6':''?>.css" />
		<link rel="shortcut icon" href="/favicon.ico" />
		<script type="text/javascript" src="/jshax.js"></script>
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<?
$random=rand(1,13337);
	if ($_GET['leet']==1 or $random==10432)
	{
?>
		<style type="text/css">
			body { 
			background-image: url(/images/ninja3.gif);
			background-position: center; 
			}
		</style>
<?
	}
	elseif ($_GET['leet']==2 or $random==369)
	{
?>
		<style type="text/css">
			body { 
			background-image: url(/images/mudkipz.gif);
			background-position: center; 
			}
		</style>
<?
	}
	elseif ($_GET['leet']==3 or$random==5622 or (date('md') == '0229' and basename($_SERVER['PHP_SELF']) == 'index.php')) // Ant's birthday
	{
?>
		<style type="text/css">
			body { 
			background-image: url(/images/antlovesyou.gif);
			background-position: center;
			}
		</style>
<?
	}
	/**
	else if ($random<13337/2)
	{
?>
		<style type="text/css">
			body { 
			background-image: url(/images/bangs_birthday_2011.jpg);
			background-position: center; 
			}
		</style>
<?
	}
	else
	{
?>
		<style type="text/css">
			body { 
			background-image: url(/images/antlovesyou.gif);
			background-position: center; 
			}
		</style>
<?
	}
	/**/
?>
	</head>
	<body>
<?
    if ($i_have_been_redirected_from_coza) {
        echo '<!--You should change your bookmarks. -->';
    }
?>
		<div style="width: 840px; margin: 10px auto; border: 2px solid #7b8385; background: #fbf6ed; text-align: center; padding: 20px 0;">
			<h2>TWHL is currently in read-only mode</h2>
			<p style="margin: 10px 0;">
				<a href="https://twhl-beta.info">TWHL4</a> is coming soon!
			</p>
		</div>
		<div class="container">
			<div class="header">
				<p class="right-info">	
<?

$thenow=gmt(U);
$thepage=mysql_real_escape_string(basename($_SERVER['PHP_SELF'],'.php'));

if (isset($_SESSION["uid"])) { 
	#Update the last action time and place.
	$theid=mysql_real_escape_string($_SESSION["usr"]);
	mysql_query("UPDATE users SET lastclick  = '$thenow', lastplace = '$thepage' WHERE  userID = '$theid'");
}

$thispagehits = mysql_query("SELECT * FROM pagehits WHERE hitpage = '$thepage'");
if (mysql_num_rows($thispagehits) > 0) mysql_query("UPDATE pagehits SET hits = hits+1 WHERE hitpage = '$thepage'");
else mysql_query("INSERT INTO pagehits (hits,hitpage,hitstart) VALUES ('1','$thepage','$thenow')");

$newcq = mysql_query('SELECT * FROM compos ORDER BY compclose Desc LIMIT 1');

$compst = "Keep your eye out for new contests here!";

if (mysql_num_rows($newcq) == 1)
{
	$newcr = mysql_fetch_array($newcq);
	
	if ($newcr['compclose'] < gmt("U"))
	{	
		if (trim($newcr['compclosedesc']) != "") $compst = 'Check out <a href="/competitions.php?results='.$newcr['compID'].'">'.$newcr['compname'].'</a> competition results!';
		else $compst = '<a href="/competitions.php?comp='.$newcr['compID'].'">'.$newcr['compname'].'</a> competition results on their way...';
	}
	elseif ($newcr['compopen'] > gmt("U"))
	{
		$compst = 'Get ready for <a href="/competitions.php?comp='.$newcr['compID'].'">'.$newcr['compname'].'</a>, our upcoming competition!';
	}
	else
	{
		$compst = 'Enter <a href="/competitions.php?comp='.$newcr['compID'].'">'.$newcr['compname'].'</a>, our current competition!';
	}
}

$newtq = mysql_query('SELECT * FROM tutorials WHERE waiting = 0 ORDER BY date DESC LIMIT 1');

$tutst = "Watch this space for newest tutorials!";

if (mysql_num_rows($newtq) == 1)
{
	$newtr = mysql_fetch_array($newtq);
	$tutst = 'Check out <a href="/tutorial.php?id='.$newtr['tutorialID'].'">'.$newtr['name']."</a>, our newest tutorial!";
}

$newuq = mysql_query("SELECT * FROM users WHERE lvl > -1 ORDER BY userID DESC LIMIT 1");

$usrst = "New members will be displayed here";

if (mysql_num_rows($newuq) == 1)
{
	$newur = mysql_fetch_array($newuq);
	if (rand(0,100) > 50)
		$usrst = 'Welcome, <a href="/user.php?id='.$newur['userID'].'">'.$newur['uid'].'</a>, our newest member!';
	else
		$usrst = 'Say hello to <a href="/user.php?id='.$newur['userID'].'">'.$newur['uid'].'</a>, our newest member!';
}
	

echo "$compst<br />
	 $tutst<br />
	 $usrst";

$base=basename($_SERVER['PHP_SELF']);

?>

				</p>
				<a href="/index.php"><img src="<?
if (date('n') == 12)
{
    $xmas=mt_rand(1,100);
    if ($xmas > 90) echo '/images/xmas_melt.gif';
    else if ($xmas > 60) echo '/images/xmas_laser.gif';
    else if ($xmas > 30) echo '/images/xmas_snow.jpg';
    else echo '/images/xmas_kitty.jpg';
} else {
    echo '/images/logo_final2.jpg';
}
?>" alt="logo" /></a>
			</div>
			<div class="search"> 
<? include 'loginheader.php'; ?>
				<form style="height: 22px;" action="/searchredir.php" method="post">
					<fieldset style="display: inline;">
						Search for: <input type="text" size="16" name="sstr" <?=(isset($_GET['searchstring']))?'value="'.htmlfilter($_GET['searchstring']).'" ':''?>/> in 
						<select name="sloc">
							<option value="tuts"<?=(isset($_GET['searchlocation']) && $_GET['searchlocation'] == "tuts")?' selected="selected"':''?>>Tutorials</option>
							<option value="ents"<?=(isset($_GET['searchlocation']) && $_GET['searchlocation'] == "ents")?' selected="selected"':''?>>Entities</option>
							<option value="gloss"<?=(isset($_GET['searchlocation']) && $_GET['searchlocation'] == "gloss")?' selected="selected"':''?>>Glossary</option>
							<option value="maps"<?=(isset($_GET['searchlocation']) && $_GET['searchlocation'] == "maps")?' selected="selected"':''?>>Maps</option>
							<option value="users"<?=(isset($_GET['searchlocation']) && $_GET['searchlocation'] == "users")?' selected="selected"':''?>>Members</option>
							<option value="forums"<?=(isset($_GET['searchlocation']) && $_GET['searchlocation'] == "forums")?' selected="selected"':''?>>Forum Posts</option>
						</select>
						<input type="submit" value="Go!" />
					</fieldset>
				</form>
			</div>