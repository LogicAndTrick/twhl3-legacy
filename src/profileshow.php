<?php
		
	if (isset($_GET['name']))
	{
		$usrnm=mysql_real_escape_string($_GET['name']);
		$result = mysql_query("SELECT * FROM users LEFT JOIN userlevels ON levelnum = lvl WHERE uid='$usrnm'");
	}
	elseif (isset($_GET['id']))
	{
		$usrid=mysql_real_escape_string($_GET['id']);
		$result = mysql_query("SELECT * FROM users LEFT JOIN userlevels ON levelnum = lvl WHERE userID='$usrid'");
	}
	
	if (mysql_num_rows($result) == 0) fail("User not found","index.php");
	
	$row = mysql_fetch_array($result);
	$user=$row['uid'];
	$access=$row['levelname'];	//axslvl($row['lvl']);
	$logins=$row['log'];
	$usrid=mysql_real_escape_string($row['userID']);
	$datej=$row['joined'];
	$dated=date("jS F Y",$datej);
	$yes=1;
	
	if (($row['allowtitle'] > 0 || $row['lvl'] >= 20) && $row['usetitle'] > 0 && $row['title'] != "") $access = $row['title'];
	
	mysql_query("UPDATE users SET stat_profilehits = stat_profilehits+1 WHERE userID = '$usrid' LIMIT 1");
	
	$avtype=$row['avtype'] ;
	$avatar=getresizedavatar($usrid,$avtype,100);
		
	$email=$row['email'];
	$allow=$row['allowemail'];
	$lastlog=$row['lastlogin'];
	$bio=$row['bio'];
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
	$location=$row['info_location'];
	$langs=$row['info_lang'];
	$aim=$row['info_aim'];
	$msn=$row['info_msn'];
	$xfire=$row['info_xfire'];
	
	$hlmap=$row['skill_hl_map'];
	$hlmodel=$row['skill_hl_model'];
	$hlcode=$row['skill_hl_code'];
	$srcmap=$row['skill_src_map'];
	$srcmodel=$row['skill_src_model'];
	$srccode=$row['skill_src_code'];
	
	$skillhl = "";
	$skillsrc = "";
	
	$numhls = $hlmap + $hlmodel + $hlcode;
	$numsrc = $srcmap + $srcmodel + $srccode;
	
	if ($numhls > 0) $skillhl = "Half-Life ".(($hlmap)?("mapper".(($numhls > 1)?(($numhls > 2)?", ":" and "):"")):"").(($hlmodel)?"modeller".(($hlcode)?" and coder":""):(($hlcode)?"coder":""));
	
	if ($numsrc > 0) $skillsrc = "Source ".(($srcmap)?("mapper".(($numsrc > 1)?(($numsrc > 2)?", ":" and "):"")):"").(($srcmodel)?"modeller".(($srccode)?" and coder":""):(($srccode)?"coder":""));
	
	$lastl=gmt(U)-$lastlog;
	$lastd=$lastl/86400;
	
	if ($lastd <= 1)
		$lastlogday="Today";
	else if ($lastd < 2)
		$lastlogday="1 Day Ago";
	else
		$lastlogday=ceil($lastd) . " Days Ago";
		
	if ($website=="")
		$website = "";
	elseif (substr($website,0,7)=="http://")
		$website = '<a href="' . $website . '">' . $website . '</a>';
	else
		$website = '<a href="http://' . $website . '">http://' . $website . '</a>';
		
		
	if ($allow!=1)
		$email = "";
	
	$dayz=gmt(U)-$datej;
	$days=ceil($dayz/86400);
	
	$currenttab = 'user';
	if (isset($_GET['user'])) $currenttab = 'user';
	elseif (isset($_GET['journals'])) $currenttab = 'journal';
	elseif (isset($_GET['maps'])) $currenttab = 'map';
	
	include 'profileuser.php';
	include 'profilejournals.php';
	include 'profilemaps.php';
?>