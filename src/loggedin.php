<?php
if (isset($uid))
{
	$topicalerts = mysql_num_rows(mysql_query("SELECT * FROM threadtracking WHERE trackuser = '$usr' AND isnew > 0"));
	$pmalerts = mysql_num_rows(mysql_query("SELECT * FROM pminbox WHERE pmto = '$usr' AND isnew = '1'"));
	$useralerts = mysql_num_rows(mysql_query("SELECT * FROM alertuser WHERE alertuser = '$usr' AND isnew = '1'"));;
?>
	<h1>Welcome back!</h1>
	<h2>This is your <?=$_SESSION['log'].numex($_SESSION['log'])?> login.</h2>
	<p class="user-panel">
		<a href="privmsg.php"><img src="images/icon_pm.png" alt="pm icon" /> <?=$pmalerts?> New Private Message<?=($pmalerts != 1)?'s':''?></a><br />
		<a href="user.php?control&amp;tracking"><img src="images/icon_topic.png" alt="tracking icon" /> <?=$topicalerts?> Topic Alert<?=($topicalerts != 1)?'s':''?></a><br />
		<!--<a href=""><span><img src="images/icon_project.png" alt="project icon" /></span>1 New Project Update</a>-->
		<a href="user.php?control"><img src="images/icon_options.png" alt="options icon" /> <?=($useralerts > 0)?$useralerts.' User Alert'.(($useralerts != 1)?'s':''):'My Control Panel'?></a><br />
<?
		if ($_SESSION['lvl'] >= 20)
		{
			$adminalerts = mysql_num_rows(mysql_query("SELECT * FROM alertadmin LEFT JOIN alerttypes ON alerttype = alerttypeID WHERE isnew = '1' AND typelevel <= '$lvl'"));
?>
		<a href="admin.php?alerts"><img src="images/icon_admin.png" alt="admin icon" /> <?=($adminalerts > 0)?$adminalerts.' Admin Alert'.(($adminalerts != 1)?'s':''):'Admin Panel'?></a><br />
<?
		}
?>
		<a href="logout.php"><img src="images/icon_logout.png" alt="logout icon" /> Logout</a>
	</p>
<?
}
else
{
?>
	<h1>Login</h1>
	<h2>Input your details below</h2>
	<form class="login" action="lognow.php" method="post">
		<p>
			Username: <input type="text" name="name" size="16" /> <br />
			Password: <input type="password" name="pass" size="16" /> <br />
			<input type="submit" value="Log me in!" /> <br /><br />
			Not a member? <a href="register.php">Register here</a>.
		</p>
	</form>
<?
}
?>