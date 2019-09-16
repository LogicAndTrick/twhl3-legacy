<?
	include 'top.php';
	if (!(isset($lvl) && ($lvl >= 40))) fail("You aren't logged in, or you don't have permission to do this.","index.php");
	
	$new_users = mysql_query("SELECT * FROM users WHERE lvl >= 0 ORDER BY userID DESC LIMIT 50");
	
?>
<div class="single-center">
	<h1>The Spammer Obliterator</h1>
<?
	if (isset($_GET['success'])) {
?>
	<p style="font-size: 30px; padding: 20px; color: green; line-height: 40px;">Spammer(s) successfully obliterated.</p>
<?
	}
?>
	<p class="single-center-content">This handy (and DANGEROUS) tool will let you kill spammers. Does the following:</p>
	<ul>
		<li>Deletes user account</li>
		<li>Bans the user's IP</li>
		<li>Deletes forum topics</li>
		<li>Deletes forum posts</li>
		<li>Deletes journals</li>
		<li>Deletes maps</li>
		<li>Deletes journal comments</li>
		<li>Deletes map comments</li>
		<li>Deletes MOTM comments</li>
		<li>Deletes news comments</li>
		<li>Deletes tutorial comments</li>
		<li>Deletes wiki comments</li>
		<li>Deletes PMs</li>
		<li>Deletes Shouts</li>
	</ul>
	<p style="font-size: 30px; padding: 20px; color: red; line-height: 40px;">DO NOT USE THIS TO DELETE NON-SPAMMER ACCOUNTS! IT PERMANENTLY BANS BY IP ADDRESS!</p>
	
	<form action="oblit_kill.php" method="post">
	<div style="padding: 10px; line-height: 30px">
<?
	while($user = mysql_fetch_array($new_users)) {
	
?>
	<input type="checkbox" name="<?=$user['userID']?>" id="user_<?=$user['userID']?>"> <label for="user_<?=$user['userID']?>"><?=$user['uid']?></label><br />
<?
	
	}
?>
	<input type="submit" value="Obliterate selected users (IRREVERSABLE!)" />
	</div>
	</form>
</div>
	
<?
	include 'bottom.php';
?>