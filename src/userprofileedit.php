<?
	include 'middle.php';
	
	if (!(isset($usr) && ($usr != ""))) fail("You must be logged in to view this page.","index.php",true);
	$peditq = mysql_query("SELECT * FROM users WHERE userID = '$usr'");
	if (mysql_num_rows($peditq) == 0) fail("You don't exist.","index.php",true);
	
	$peditr = mysql_fetch_array($peditq);
	$allowtitle = $peditr['allowtitle'];
	$user_level = $peditr['lvl'];
	$oldusetitle = $peditr['usetitle'];
	$oldtitle = $peditr['title'];
	
	$newpass = false;
	if ((isset($_POST['pass1']) && $_POST['pass1'] != "") || (isset($_POST['pass2']) && $_POST['pass2'] != ""))
	{
		$pass1 = strtolower(trim($_POST['pass1']));
		$pass2 = strtolower(trim($_POST['pass2']));		
		if (strlen($pass1) > 20) $pass1 = substr($pass1,0,20);
		if (strlen($pass2) > 20) $pass2 = substr($pass2,0,20);
		$pass1 = md5($pass1);
		$pass2 = md5($pass2);
		if ($pass1 != $pass2) fail("The passwords didn't match. Your profile was not modified.","user.php?control&amp;edit",true);
		$newpass = true;
	}
	
	$email = htmlfilter($_POST['email']);
	if (!isset($email) || $email == "" || strpos($email,"@") === false || strpos($email,".") === false)
		fail("Your email is invalid. Your profile was not modified.","user.php?control&amp;edit",true);
	
	$timezone = htmlfilter($_POST['timezone']);
	if (!isset($timezone) || !is_numeric($timezone) || $timezone < -12 || $timezone > 12)
		fail("Your timezone is invalid. Your profile was not modified.","user.php?control&amp;edit",true);
	
	$showemail = 0;
	if ($_POST['showemail']) $showemail = 1;
	
	$usetitle = $oldusetitle;
	$title = $oldtitle;
	if ($allowtitle || $user_level >= 20)
	{
		$usetitle = ($_POST['usetitle'])?1:0;
		$title = htmlfilter($_POST['title']);
	}
	
	$realname = htmlfilter($_POST['realname']);
	$location = htmlfilter($_POST['location']);
	$language = htmlfilter($_POST['language']);
	$occupation = htmlfilter($_POST['occupation']);
	$website = htmlfilter($_POST['website']);
	$interests = htmlfilter($_POST['interests']);
	$gender = htmlfilter($_POST['gend']);
	if ($gender != "M" && $gender != "F") $gender = "?";
	
	$msn = htmlfilter($_POST['msn']);
	$aim = htmlfilter($_POST['aim']);
	$xfire = htmlfilter($_POST['xfire']);
	$steam = htmlfilter($_POST['steam']);
	
	$hlmap = ($_POST['hl1map'])?1:0;
	$hlmod = ($_POST['hl1model'])?1:0;
	$hlcod = ($_POST['hl1code'])?1:0;
	$scmap = ($_POST['srcmap'])?1:0;
	$scmod = ($_POST['srcmodel'])?1:0;
	$sccod = ($_POST['srccode'])?1:0;
	
	$forum_avs = ($_POST['forumavatars'])?1:0;
	
	$forum_posts = (int)$_POST['forumposts'];
	if ($forum_posts < 10 || $forum_posts > 50 || !is_numeric($forum_posts) || (($forum_posts%10) != 0)) $forum_posts = 50;
	
	$bio = htmlfilter($_POST['bio'],true);
	
	mysql_query("UPDATE users SET email = '$email', timezone = '$timezone', allowemail = '$showemail', usetitle = '$usetitle', title = '$title' , info_realname = '$realname', info_website = '$website', info_job = '$occupation', info_interests = '$interests', info_location = '$location', info_lang = '$language', info_aim = '$aim', info_msn = '$msn', info_xfire = '$xfire', info_steam = '$steam', gender = '$gender', skill_hl_map = '$hlmap', skill_hl_model = '$hlmod', skill_hl_code = '$hlcod', skill_src_map = '$scmap', skill_src_model = '$scmod', skill_src_code = '$sccod', bio = '$bio', opt_forum_avatar = '$forum_avs', opt_forum_posts = '$forum_posts' WHERE userID = '$usr' LIMIT 1");
	
	$_SESSION['tmz'] = $timezone;
	$_SESSION['forumavs'] = $forum_avs;
	$_SESSION['forumposts'] = $forum_posts;
	
	$tmz = $_SESSION['tmz'];
	$forumavs = $_SESSION['forumavs'];
	$forumposts = $_SESSION['forumposts'];
	
	if ($newpass)
	{
		$_SESSION['pwd'] = $pass1;
		mysql_query("UPDATE users SET pwd = '$pass1' WHERE userID = '$usr' LIMIT 1");
	}
	
	header("Location: user.php?id=$usr");
?>