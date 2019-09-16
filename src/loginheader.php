<?
	$base=basename($_SERVER['PHP_SELF']);
	if ($base != 'index.php') {
		if (isset($_SESSION['lvl']))
		{
			$topicalerts = mysql_num_rows(mysql_query("SELECT * FROM threadtracking WHERE trackuser = '$usr' AND isnew > 0"));
			$pmalerts = mysql_num_rows(mysql_query("SELECT * FROM pminbox WHERE pmto = '$usr' AND isnew = '1'"));
			$useralerts = mysql_num_rows(mysql_query("SELECT * FROM alertuser WHERE alertuser = '$usr' AND isnew = '1'"));
			$adminalerts = mysql_num_rows(mysql_query("SELECT * FROM alertadmin LEFT JOIN alerttypes ON alerttype = alerttypeID WHERE typelevel <= '$lvl' AND isnew = '1'"));
?>
				<span class="header-userpanel">	
					<? if ($pmalerts > 0) { ?><a href="privmsg.php"><img src="images/top_pm.png" alt="pm" /></a><? } ?>
					<? if ($topicalerts > 0) { ?><a href="user.php?control&tracking"><img src="images/top_topic.png" alt="topic" /></a><? } ?>
					<!--<a href="#"><img src="images/icon_project.png" alt="project" />(1)</a>-->
					<? if ($useralerts > 0) { ?><a href="user.php?control"><img src="images/top_options.png" alt="user" /></a><? } ?>
					<? if ($adminalerts > 0 && $lvl >= 20) { ?><a href="admin.php?alerts"><img src="images/top_admin.png" alt="admin" /></a><? } ?>
					<a href="logout.php"><img src="images/top_logout.png" alt="logout" /></a>
				</span>	
<?
		}
		else
		{
?>
				<span id="header-login"><a href="javascript:showloginbox()">Click to Login</a> | <a href="register.php">Register</a></span>
				<form action="lognow.php" method="post">
				<fieldset id="header-login-form">
					<span>
							<input type="hidden" name="return" value="<?=$_SERVER['PHP_SELF']."?".str_replace("&","&amp;",$_SERVER['QUERY_STRING'])?>" />
							User: <input type="text" name="name" size="16" />
							Pass: <input type="password" name="pass" size="16" />
							<input size="16" value="Login" type="submit" />
					</span>
				</fieldset>
				</form>
<?
		}
	}
?>