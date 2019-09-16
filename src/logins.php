<?php

error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED);

// Meh, whatevs.
$shoutbox_secret = '/kif';
$shoutbox_secret_trim = strlen($shoutbox_secret) + 1;

require_once("db.inc.php");

session_start();

$i_have_been_redirected_from_coza = false;
if ($_SESSION['FROM_COZA'] === true)
{
    $i_have_been_redirected_from_coza = true;
}
unset($_SESSION['FROM_COZA']);

function login_user($query)
{
	$row = mysql_fetch_array($query);
	
	$verify = $row['account_verify'];
	if (isset($verify) && strlen($verify) > 0) return;
	
	$usr = $row['userID'];
	$uid=mysql_real_escape_string($row['uid']);
	$lvl=$row['lvl'];
	$tmz=$row['timezone'];
	$log=mysql_real_escape_string($row['log']+1);
	$lst=$row['lastlogin'];
	$forumavs=($row['opt_forum_avatar'] == 1)?true:false;
	$forumposts=$row['opt_forum_posts'];
	if ($forumposts < 0 || $forumposts > 50 || !is_numeric($forumposts) || (($forumposts%10) != 0)) $forumposts = 50;
	$wiki_lvl=$row['wiki_lvl'];
	$thenow=gmt(U);
	
	//random salt, gotta remain reasonably consistant.
	$rand_log = md5($uid . md5(md5(md5(md5(md5($pword))))) . md5("-----") . md5($uid . md5($usr . "-----")));
	
	mysql_query("UPDATE users SET log = '$log' WHERE uid = '$uid'");
	mysql_query("UPDATE users SET cookie = '$rand_log' WHERE uid = '$uid'");
	mysql_query("UPDATE users SET  lastlogin = '$thenow' WHERE uid = '$uid'");
	if ($row['ipadd']!=$_SERVER['REMOTE_ADDR']) // if IP is different, update to new IP.
	{
		$ipadd=mysql_real_escape_string($_SERVER['REMOTE_ADDR']);
		mysql_query("UPDATE users SET ipadd = '$ipadd' WHERE uid = '$uid'");
	}
	
	setcookie ("twhl_username",$usr,time()+30000000);
	setcookie ("twhl_log",$rand_log,time()+30000000);
	
	$_SESSION['uid'] = $uid;
	$_SESSION['lvl'] = $lvl;
	$_SESSION['log'] = $log;
	$_SESSION['usr'] = $usr;
	$_SESSION['tmz'] = $tmz;
	$_SESSION['lst'] = $lst;
	$_SESSION['forumavs'] = $forumavs;
	$_SESSION['forumposts'] = $forumposts;
	$_SESSION['use_wide'] = $use_wide;
	$_SESSION['wiki_lvl'] = $wiki_lvl;
}
if (true) {} else
// if not logged in, and user/pass are valid
if (!isset($_SESSION['uid']) && isset($_POST['name']) && ($_POST['name']!="") && ($_POST['pass']!=""))
{
	// get username, encode password
	// random trivia: the TWHL before TWHL3 (this version) didn't encode the password!
	// why is password case insensitive? thats the way TWHL did it before and it's one of those things that must stay.
	$uname = mysql_real_escape_string(trim($_POST['name']));
	$pwtemp1 = $_POST['pass'];
	if (strlen($pwtemp1) > 20) $pwtemp1 = substr($pwtemp1,0,20);
	$pword = md5(strtolower(trim($pwtemp1)));
	$result = mysql_query("SELECT * FROM users WHERE uid LIKE '$uname' AND pwd = '$pword'");
	if(mysql_num_rows($result) == 1) login_user($result);
}
elseif (!isset($_SESSION['uid']) && isset($_COOKIE['twhl_username']))
{
	// user is not logged in, but has a cookie
	$uname = mysql_real_escape_string($_COOKIE["twhl_username"]);
	$urand = mysql_real_escape_string($_COOKIE["twhl_log"]);
	$cookieq = mysql_query("SELECT * FROM users WHERE userID = '$uname' AND cookie = '$urand'",$dbh);
	if (mysql_num_rows($cookieq) == 1) login_user($cookieq);
	else
	{
		// Delete the cookie, it is invalid
		setcookie ("twhl_username","",time()-30000000);
		setcookie ("twhl_log","",time()-30000000);
	}
}

if (true) { session_destroy(); } else
// if the user has just successfully logged in, or is already logged in
if (isset($_SESSION['uid']))
{
	//user is logged in
	$uid = $_SESSION['uid'];
	$pwd = $_SESSION['pwd'];
	$lvl = $_SESSION['lvl'];
	$log = $_SESSION['log'];
	$usr = $_SESSION['usr'];
	$tmz = $_SESSION['tmz'];
	$lst = $_SESSION['lst'];
	$forumavs = $_SESSION['forumavs'];
	$forumposts = $_SESSION['forumposts'];
	$use_wide = $_SESSION['use_wide'];
	$wiki_lvl = $_SESSION['wiki_lvl'];
}
elseif (!isset($_SESSION['security_code']))
{
	// user is not logged in
	$_SESSION = array();
	session_destroy();
}

//BANNAGE LAL
// random trivia: the banned page is THE GOVERNATOR.
if (isset($_SESSION['usr'])) //check for user account ban
{
	if (banned($_SESSION['usr'])) {
        header("Location: banned.php?id=".$_SESSION['usr']);
        die();
    }
}
else //check for ip ban. users cannot be ip banned and not user banned, hence the else.
{
	if (ipbanned($_SERVER['REMOTE_ADDR'])) {
        header("Location: banned.php");
        die();
    }
}

//include '_twhl_upgrade_redirect.php';
//include '_twhl_maintenance_redirect.php';
?>