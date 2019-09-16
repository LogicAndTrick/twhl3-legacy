<?
	include 'middle.php';
	
	if (!(isset($usr) && ($usr != ""))) fail("You must be logged in to view this page.","index.php",true);
	if (!(isset($lvl) && ($lvl >= 40))) fail("You must be an admin to view this page! Get the hell out!","index.php",true);
	
	if (!(isset($_POST['user_id']) && $_POST['user_id'] != "")) fail("No user specified.","index.php",true);
	$user_id = mysql_real_escape_string($_POST['user_id']);
	$peditq = mysql_query("SELECT * FROM users WHERE userID = '$user_id'");
	if (mysql_num_rows($peditq) == 0) fail("This user doesn't exist.","index.php",true);
	
	if (!(isset($_POST['username']) && $_POST['username'] != ""))
		fail("That username is invalid. The profile was not modified.","user.php?manage=$user_id&amp;edit",true);
	$username = $_POST['username'];
	$username = str_replace("\n", "", $username);
	$username = str_replace("\r", "", $username);
	$username = htmlfilter($username);
	
	$usrnmq = mysql_query("SELECT * FROM users WHERE uid = '$username' AND userID != '$user_id'");
	if (mysql_num_rows($usrnmq) > 0)
		fail ("That username already exists. The profile was not modified.","user.php?manage=$user_id&amp;edit",true);
	
	$level = htmlfilter($_POST['level']);
	if (!isset($level) || !is_numeric($level) || $level < 0 || $level > 55)
		fail("That level is invalid. The profile was not modified.","user.php?manage=$user_id&amp;edit",true);
	
	$email = htmlfilter($_POST['email']);
	if (!isset($email) || $email == "" || strpos($email,"@") === false || strpos($email,".") === false)
		fail("That email is invalid. The profile was not modified.","user.php?manage=$user_id&amp;edit",true);
	
	$timezone = htmlfilter($_POST['timezone']);
	if (!isset($timezone) || !is_numeric($timezone) || $timezone < -12 || $timezone > 12)
		fail("That timezone is invalid. The profile was not modified.","user.php?manage=$user_id&amp;edit",true);
	
	$showemail = 0;
	if ($_POST['showemail']) $showemail = 1;
	
	$allowtitle = ($_POST['allowtitle'])?1:0;
	$usetitle = ($_POST['usetitle'])?1:0;
	$title = htmlfilter($_POST['title']);
	
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
	
	mysql_query("UPDATE users SET uid = '$username', lvl = '$level', email = '$email', timezone = '$timezone', allowemail = '$showemail', allowtitle = '$allowtitle', usetitle = '$usetitle', title = '$title', info_realname = '$realname', info_website = '$website', info_job = '$occupation', info_interests = '$interests', info_location = '$location', info_lang = '$language', info_aim = '$aim', info_msn = '$msn', info_xfire = '$xfire', info_steam = '$steam', gender = '$gender', skill_hl_map = '$hlmap', skill_hl_model = '$hlmod', skill_hl_code = '$hlcod', skill_src_map = '$scmap', skill_src_model = '$scmod', skill_src_code = '$sccod', bio = '$bio', opt_forum_avatar = '$forum_avs', opt_forum_posts = '$forum_posts' WHERE userID = '$user_id' LIMIT 1");
	
	header("Location: user.php?manage=$user_id&edit");
?>